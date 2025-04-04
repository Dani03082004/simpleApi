<?php

namespace Api;
use Api\http\Request;

class Router{
    protected Request $request;

    function __construct(Request $request)
    {
        $this->request = $request;
    }

    function dispatch(){
        // Primero cargar rutas
        $routes = json_decode(file_get_contents('src/routes.json'), true);
        // dd($routes);
        // Segundo lugar comprobar si path existe
        // lanzar el handler-->controlador
    }
}