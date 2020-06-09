<?php
// FRONT CONTROLLER

// 1. Общие настройки
use Phroute\Phroute\RouteCollector;

ini_set('display_errors',1);
error_reporting(E_ALL);

session_start();

require dirname(__DIR__).'/vendor/autoload.php';

/** @var RouteCollector $router */
$router = require dirname(__DIR__).'/config/routes.php';

$dispatcher = new Phroute\Phroute\Dispatcher($router->getData());

$response = $dispatcher->dispatch(
    $_SERVER['REQUEST_METHOD'],
    parse_url($_SERVER['REQUEST_URI'],
        PHP_URL_PATH
    )
);

echo $response;
