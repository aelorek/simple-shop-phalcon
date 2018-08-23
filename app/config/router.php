<?php

$router = $di->getRouter();


$router->add('/{page:[0-9]*}', [
    'controller' => 'Product',
    'action'     => 'list',
    'page'       => 0,
]);

$router->add('/login', [
    'controller' => 'auth',
    'action'     => 'login',
]);

$router->add('/admin/new-product', [
    'controller' => 'product',
    'action'     => 'add',
]);


$router->handle();
