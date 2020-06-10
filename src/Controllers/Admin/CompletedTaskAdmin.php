<?php

declare(strict_types=1);


namespace TaskManager\Controllers\Admin;


use TaskManager\Repository\AdminRepository;
use TaskManager\Repository\TaskRepository;
use Twig\Environment;

final class CompletedTaskAdmin
{
    private Environment $twig;
    private TaskRepository $repository;
    private AdminRepository $adminRepository;

    /**
     * CreateTasks constructor.
     * @param Environment $twig
     * @param TaskRepository $repository
     * @param AdminRepository $adminRepository
     */
    public function __construct(
        Environment $twig,
        TaskRepository $repository,
        AdminRepository $adminRepository
    ){
        $this->twig = $twig;
        $this->repository = $repository;
        $this->adminRepository = $adminRepository;
    }
}