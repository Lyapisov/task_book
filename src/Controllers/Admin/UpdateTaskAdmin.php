<?php

declare(strict_types=1);


namespace TaskManager\Controllers\Admin;


use TaskManager\Model\Task;
use TaskManager\Repository\AdminRepository;
use TaskManager\Repository\TaskRepository;
use Twig\Environment;

final class UpdateTaskAdmin
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
        $taskInfo = $this->repository->getTaskById($id);

        if (!isset($_POST['submit'])) {
            return $this->twig->render('admin/update.php.twig',
                [
                    'taskInfo' => $taskInfo
                ]);
        }

        $taskName = $_POST['name'] ?? null;
        $userName = $_POST['user_name'] ?? null;
        $userEmail = $_POST['user_email'] ?? null;

        $errors = [];

        if (!$this->adminRepository->checkName((string)$taskName)) {
            $errors[] = 'Введите задачу !';
        }

        if (!$this->adminRepository->checkName((string)$userName)) {
            $errors[] = 'Введите имя !';
        }

        if (!$this->adminRepository->checkEmail((string)$userEmail)) {
            $errors[] = 'Введите верный email !';
        }

        if (!$errors) {
            $task = new Task((string)$taskName, (string)$userName, (string)$userEmail);
            if(!isset($_POST['completed'])){
                $this->repository->updateTaskById($task);
                $successful = 'Задача успешно изменена.';
                return $this->twig->render('admin/update.php.twig',[
                    'successful' => $successful
                ]);
            }
            $this->repository->updateCompleted($id);
        }
        header("Location: /");
        return '';
    }
}