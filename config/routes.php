<?php


use Phroute\Phroute\RouteCollector;
use TaskManager\Controllers\Admin\AuthAdmin;
use TaskManager\Controllers\Admin\LogoutAdmin;
use TaskManager\Controllers\Admin\UpdateTaskAdmin;
use TaskManager\Controllers\CreateTasks;
use TaskManager\Controllers\GetTasks;

$container = require dirname(__DIR__).'/config/services.php';

$router = new RouteCollector();

$router->any('/', function() use ($container) {
    $controller = $container[GetTasks::class];
    return $controller();
});

$router->any('/create', function() use ($container) {
    $controller = $container[CreateTasks::class];
    return $controller();
});

$router->any('/update/{id}', function($id) use ($container) {
    $controller = $container[UpdateTaskAdmin::class];
    return $controller($id);
});

$router->post('/update/completed/{id}', function($id) use ($container) {
    $controller = $container[LogoutAdmin::class];
    return $controller($id);
});

$router->any('/auth', function() use ($container) {
    $controller = $container[AuthAdmin::class];
    return $controller();
});

$router->any('/logout', function() use ($container) {
    $controller = $container[LogoutAdmin::class];
    return $controller();
});



return $router;