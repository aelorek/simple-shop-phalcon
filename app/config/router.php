<?php

if(isset($di)) {
    $router = $di->getRouter();
} else {
    $router = new Phalcon\Mvc\Router();
}


$router->add('/{page:[0-9]*}', [
    'namespace'  => 'App\\Controllers',
    'controller' => 'product',
    'action'     => 'list',
    'page'       => 0,
])->setName('home');

$router->add('/login', [
    'namespace'  => 'App\\Controllers',
    'controller' => 'auth',
    'action'     => 'login',
]);

$router->add('/logout', [
    'namespace'  => 'App\\Controllers',
    'controller' => 'auth',
    'action'     => 'logout',
]);

$router->add('/admin/new-product', [
    'namespace'  => 'App\\Controllers',
    'controller' => 'product',
    'action'     => 'add',
]);


$router->handle();
