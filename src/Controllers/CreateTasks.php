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

        $taskName = $_POST['name'] ?? '';
        $userName = $_POST['user_name'] ?? '';
        $userEmail = $_POST['user_email'] ?? '';

        $errors = [];

        try {
            $task = new Task((string)$taskName, (string)$userName, (string)$userEmail);
            $task->checkValue($taskName, $userName, $userEmail);
            $this->repository->add($task);
            $successful = 'Задача успешно размещена.';

            return $this->twig->render('task/createTask.php.twig',[
                'successful' => $successful
            ]);
        } catch (\DomainException $exception){
            $errors[] = $exception->getMessage();
        }

        return $this->twig->render('task/createTask.php.twig',[
            'errors' => $errors
        ]);
    }
}