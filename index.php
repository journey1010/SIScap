<?php

include_once 'model/RutasRelativas.php';
require_once _ROOT_CONTROLLER . 'RouterController.php';

$router = new Router();
if($router->secure){
    $router->loadRoutesFromJson();
    $router->handleRequest();
}else{
    header('Location: https://' . $_SERVER['HTTP_HOST'] . filter_var( $_SERVER['REQUEST_URI']), FILTER_SANITIZE_URL);
    exit;
}