<?php


use Phroute\Phroute\RouteCollector;
use TaskManager\Controllers\Admin\AuthAdmin;
use TaskManager\Controllers\Admin\CompleteTask;
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

$router->any('/page/{id:\d+}', function(int $page) use ($container) {
    $controller = $container[GetTasks::class];
    return $controller($page);
});

$router->any('/create', function() use ($container) {
    $controller = $container[CreateTasks::class];
    return $controller();
});

$router->any('/update/{id}', function(int $id) use ($container) {
    $controller = $container[UpdateTaskAdmin::class];
    return $controller($id);
});

$router->any('/update/completed/{id}', function(int $id) use ($container) {
    $controller = $container[CompleteTask::class];
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