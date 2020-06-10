<?php

use TaskManager\Controllers\Admin\AuthAdmin;
use TaskManager\Controllers\Admin\CompletedTaskAdmin;
use TaskManager\Controllers\Admin\LogoutAdmin;
use TaskManager\Controllers\Admin\UpdateTaskAdmin;
use TaskManager\Controllers\CreateTasks;
use TaskManager\Repository\AdminRepository;
use TaskManager\Services\Db;
use TaskManager\Controllers\GetTasks;
use TaskManager\Repository\TaskRepository;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$twigLoader = new FilesystemLoader(dirname(__DIR__).'/views');
$twig = new Environment($twigLoader);
$pdo = Db::getConnection();
$container = [];

$container[TaskRepository::class] = new TaskRepository($pdo);
$container[AdminRepository::class] = new AdminRepository($pdo);
$container[GetTasks::class] = new GetTasks(
    $twig,
    $container[TaskRepository::class],
    $container[AdminRepository::class],
);
$container[CreateTasks::class] = new CreateTasks($twig, $container[TaskRepository::class]);
$container[UpdateTaskAdmin::class] = new UpdateTaskAdmin(
    $twig,
    $container[TaskRepository::class],
    $container[AdminRepository::class]
);
$container[AuthAdmin::class] = new AuthAdmin($twig, $container[AdminRepository::class]);
$container[LogoutAdmin::class] = new LogoutAdmin();
$container[CompletedTaskAdmin::class] = new CompletedTaskAdmin(
    $twig,
    $container[TaskRepository::class],
    $container[AdminRepository::class]
);

return $container;