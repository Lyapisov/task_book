<?php



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
        $page = $_GET['page'] ?? 1;

        $login = $this->adminRepository->isGuest();
        $choice = $_POST['sort'] ?? '';
        $paginator = $this->repository->getAllByFilter($choice, $page,self::NUMBER_OF_TASKS);
        return $this->twig->render('home/index.php.twig',
            [
                'tasks' => $paginator->getCurrentPageResults(),
                'login' => $login,
                'paginator' => $paginator
            ]
        );
    }
}