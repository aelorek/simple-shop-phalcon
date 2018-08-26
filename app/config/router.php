<?php

if(isset($di)) {
    $router = $di->getRouter();
} else {
    $router = new Phalcon\Mvc\Router();
}


$router->add('/{page:[0-9]*}', [
    'controller' => 'product',
    'action'     => 'list',
    'page'       => 0,
])->setName('home');

$router->add('/login', [
    'controller' => 'auth',
    'action'     => 'login',
]);

$router->add('/logout', [
    'controller' => 'auth',
    'action'     => 'logout',
]);

$router->add('/admin/new-product', [
    'controller' => 'product',
    'action'     => 'add',
]);


$router->handle();
