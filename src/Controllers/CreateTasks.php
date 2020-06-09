<?php

declare(strict_types=1);


namespace TaskManager\Controllers;


use TaskManager\Model\Task;
use TaskManager\Repository\TaskRepository;
use Twig\Environment;

final class CreateTasks
{
    private Environment $twig;
    private TaskRepository $repository;

    /**
     * CreateTasks constructor.
     * @param Environment $twig
     * @param TaskRepository $repository
     */
    public function __construct(Environment $twig, TaskRepository $repository)
    {
        $this->twig = $twig;
        $this->repository = $repository;
    }

    public function __invoke():string
    {
        if (!isset($_POST['submit'])) {
            return $this->twig->render('task/createTask.php.twig');
        }

        $taskName = $_POST['name'] ?? null;
        $userName = $_POST['user_name'] ?? null;
        $userEmail = $_POST['user_email'] ?? null;

        $errors = [];

        if (!$this->repository->checkName((string)$taskName)) {
            $errors[] = 'Введите задачу !';
        }

        if (!$this->repository->checkName((string)$userName)) {
            $errors[] = 'Введите имя !';
        }

        if (!$this->repository->checkEmail((string)$userEmail)) {
            $errors[] = 'Введите верный email !';
        }
        if (!$errors) {
            $task = new Task((string)$taskName, (string)$userName, (string)$userEmail);
            $this->repository->add($task);
            $successful = 'Задача успешно размещена.';

            return $this->twig->render('task/createTask.php.twig',[
                'successful' => $successful
            ]);
        }

        return $this->twig->render('task/createTask.php.twig',[
            'errors' => $errors
        ]);
    }
}