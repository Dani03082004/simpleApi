<?php

namespace Api\http;

class Response
{
    protected $statusCode = 200;
    protected $headers = [];
    protected $body = '';

    function __construct()
    {
        $this->headers = [
            'Content-Type' => 'application/json',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization'
        ];
    }
    function json(array $data){
        $this->headers['Content-Type'] = 'application/json';
        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }
        echo json_encode($data);
    }
}
