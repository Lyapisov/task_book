<?php

declare(strict_types=1);


namespace TaskManager\Controllers;


use TaskManager\Repository\AdminRepository;
use TaskManager\Repository\TaskRepository;
use Twig\Environment;

final class GetTasks
{
    const NUMBER_OF_TASKS = 3;
    private Environment $twig;
    private TaskRepository $repository;
    private AdminRepository $adminRepository;

    public function __construct(
        Environment $twig,
        TaskRepository $repository,
        AdminRepository $adminRepository
    ){
        $this->twig = $twig;
        $this->repository = $repository;
        $this->adminRepository = $adminRepository;
    }

    public function __invoke(): string
    {

        $login = $this->adminRepository->isGuest();
        $standartChoice = '';

        if(!isset($_POST['submit'])) {
            $tasks = $this->repository->getAllByFilter($standartChoice, 1,self::NUMBER_OF_TASKS);
            return $this->twig->render('home/index.php.twig',
                [
                    'tasks' => $tasks,
                    'login' => $login
                ]
            );
        }
        $choice = $_POST['sort'];
        $tasks = $this->repository->getAllByFilter($choice, 1,self::NUMBER_OF_TASKS);
        return $this->twig->render('home/index.php.twig',
            [
                'tasks' => $tasks,
                'login' => $login
            ]
        );
    }
}