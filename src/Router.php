<?php

namespace Api;

use Api\http\Request;

class Router
{
    protected Request $request;
    protected $routes = [];

    function __construct(Request $request)
    {
        $this->request = $request;
        $this->routes = json_decode(file_get_contents('src/routes.json'), true);
        //dd($this->routes);
    }

    private function matchUri(string $routepath, string $requestUri, &$params): bool
    {
        $pattern = preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $routepath);
        $pattern = "#^" . $pattern . "$#";

        if (preg_match($pattern, $requestUri, $matches)) {
            $params = array_filter($matches, function ($key) {
                return is_string($key);
            }, ARRAY_FILTER_USE_KEY);

            return true;
        }

        return false;
    }

    function dispatch()
    {
        foreach ($this->routes as $route) {
            // Verificar el valor de la ruta definida
            var_dump($route["path"]);
            // Verificar la ruta de la solicitud actual
            var_dump($this->request->getPath());

            if (!isset($route["path"]) || empty($route["path"])) {
                (new \Api\Http\Response())->json(['error' => 'Invalid route path'], 500);
                return;
            }

            $params = [];

            // Compara el método de la ruta con el método de la solicitud y luego trata de hacer coincidir la ruta con la URI solicitada
            if (
                $route["method"] === $this->request->getMethod() &&
                $this->matchUri($route["path"], $this->request->getPath(), $params)
            ) {
                // Aquí se divide el controlador y el método a ejecutar
                [$class, $method] = explode("::", $route["handler"]);
                $class = "\\Api\\controllers\\" . $class;

                if (class_exists($class) && method_exists($class, $method)) {
                    // Crear el controlador
                    $controller = new $class();

                    // Usamos la reflexión para asegurarnos de pasar los parámetros correctamente
                    $reflection = new \ReflectionMethod($controller, $method);
                    $args = [$this->request];

                    // Si el método tiene más parámetros, se agregan los parámetros de la ruta
                    if ($reflection->getNumberOfParameters() > 1) {
                        $args[] = $params;
                    }

                    // Llamamos al método del controlador pasando los parámetros requeridos
                    call_user_func_array([$controller, $method], $args);
                    return;
                } else {
                    // Si el controlador o el método no existen
                    (new \Api\Http\Response())->json(['error' => 'Handler not found'], 500);
                    return;
                }
            }
        }

        // Si no se encontró una ruta coincidente
        (new \Api\Http\Response())->json(['error' => 'Route not found'], 404);
    }
}
