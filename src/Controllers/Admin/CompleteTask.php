<?php

declare(strict_types=1);


namespace TaskManager\Controllers\Admin;


use TaskManager\Repository\AdminRepository;
use TaskManager\Repository\TaskRepository;
use Twig\Environment;

final class CompleteTask
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

    public function __invoke(int $id):string
    {
        $this->adminRepository->checkAdmin();
        $task = $this->repository->getTaskById($id);
        $task->complete();
        $this->repository->update($task);
        header("Location: /");
        return '';
    }
}