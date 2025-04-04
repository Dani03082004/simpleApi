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
        $this->params = $_GET ?? [];;
        $this->body = $this->parseRequestBody();
    }

    private function getRequestHeaders(): array
    {
        $headers = [];
        return $headers;
    }

    private function parseRequestBody()
    {
        $body = []; 
        if (in_array($this->method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $contentType = $this->getHeader('Content-Type');  
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

    public function getHeader($header){
        return $this->headers[$header] ?? null;
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
}
