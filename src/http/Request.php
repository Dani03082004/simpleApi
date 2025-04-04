<?php

namespace Api\http;

class Request
{
    protected $headers = [];
    protected $params = [];
    protected $body = [];
    protected $path;
    protected $method;

    function __construct()
    {
        $this->path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->headers = $this->getRequestHeaders();
        $this->params = [];
        $this->body = $this->parseRequestBody();
    }

    private function getRequestHeaders(): array
    {
        $headers = [];
        if(function_exists('getallheaders')) {
            $headers = getallheaders();
        } else {
            echo "getallheaders() function not available";
        }
        return $headers;
    }

    private function parseRequestBody()
    {
        $body = [];
        $contentType = ''; 
        if (in_array($this->method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $contentType = $this->getHeader('Content-Type');
            dd($contentType);    
            if (stripos($contentType, 'application/json') !== false) {
                $content = file_get_contents('php://input');
                $json = json_decode($content, true);
                $body = $json ?? [];  
            }
            elseif (stripos($contentType, 'application/x-www-form-urlencoded') !== false) {
                $body = $_POST ?? [];  
            }
        }
        return $body;  
    }
    

    public function getParams()
    {
        return $this->params;
    }

    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    public function getHeader($header,$default = null){
        if(isset($this->headers[$header])){
            return $this->headers[$header];
        }
        return $default;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function setHeaders($headers)
    {
        $this->headers = $headers;
        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }
}
