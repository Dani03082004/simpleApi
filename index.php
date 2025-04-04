<?php

require 'src/Router.php';
require 'src/http/Request.php';
require 'src/helper.php';

use Api\Router;
use Api\http\Request;

// flujo del programa
$request = new Request;
$router = new Router($request);
$router->dispatch();
dd($request,$router);
?>