<?php



namespace TaskManager\Controllers;


use TaskManager\Model\Sort;
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

    public function __invoke(int $page = 1): string
    {

        $login = $this->adminRepository->isGuest();
        $choice = $_GET['sort'] ?? '';
        $paginator = $this->repository->getAllByFilter($choice, $page,self::NUMBER_OF_TASKS);
        return $this->twig->render('home/index.php.twig',
            [
                'extraParams' => http_build_query($_GET),
                'tasks' => $paginator->getCurrentPageResults(),
                'login' => $login,
                'choice' => $choice,
                'paginator' => $paginator
            ]
        );
    }
}