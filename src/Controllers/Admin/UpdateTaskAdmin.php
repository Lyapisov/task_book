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
        $task = $this->repository->getTaskById($id);

        if (!isset($_POST['submit'])) {
            return $this->twig->render('admin/update.php.twig',
                [
                    'taskInfo' => $task
                ]);
        }

        $errors = [];
        try {

            $taskName = $_POST['name'] ?? '';
            $task->edit($taskName);
            $this->repository->update($task);
            $successful = 'Задача успешно изменена.';
            return $this->twig->render('admin/update.php.twig', [
                'successful' => $successful,
                'taskInfo' => $task
            ]);
        } catch (\DomainException $exception){
            $errors[] = $exception->getMessage();
        }

        return $this->twig->render('admin/update.php.twig', [
            'errors' => $errors,
            'taskInfo' => $task
        ]);


//        if(isset($_POST['completed'])){
//            $this->repository->updateCompleted($id);
//            $successful = 'Статус изменен.';
//            return $this->twig->render('admin/update.php.twig', [
//                'successful' => $successful
//            ]);
//        }



    }
}