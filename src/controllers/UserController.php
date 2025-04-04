<?php

namespace Api\Controllers;

use Api\http\Request;
use Api\http\Response;

class UserController
{
    protected Request $request;
    protected Response $response;
    protected $users = [];

    function __construct()
    {
        $this->request;
        $this->users = [
            [
                'name' => 'Perico de los Palotes',
                'email' => 'pperico@gmail.com',
                'age' => 25,
            ],
            [
                'name' => 'Susana la Loba',
                'email' => 'lalobasusana@gmail.com',
                'age' => 30,
            ]
        ];
    }

    function index()
    {
        $response = new Response;
        $response->json($this->users);
    }

    function create(Request $request)
    {
        $this->response = new Response;
        $user = $request->getParams();
        if (isset($user['name']) && isset($user['email']) && isset($user['age'])) {
            $this->users[] = $user;
            $this->response->json(['message' => 'User created successfully'], 201);
        } else {
            $this->response->json(['error' => 'Invalid user data'], 400);
        }
    }

    public function show(Request $request, array $params)
    {
        $this->response = new Response;
        $id = $params['id'] ?? null;

        if ($id !== null && isset($this->users[$id])) {
            $this->response->json($this->users[$id]);
        } else {
            $this->response->json(['error' => 'User not found'], 404);
        }
    }
}
