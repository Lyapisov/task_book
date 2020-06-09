<?php

declare(strict_types=1);


namespace TaskManager\Controllers\Admin;

use TaskManager\Model\Admin;
use TaskManager\Repository\AdminRepository;
use Twig\Environment;

final class AuthAdmin
{
    private Environment $twig;
    private AdminRepository $repository;

    public function __construct(Environment $twig, AdminRepository $repository)
    {
        $this->twig = $twig;
        $this->repository = $repository;
    }

    public function __invoke(): string
    {

        if(!isset($_POST['submit'])){
           return $this->twig->render('admin/auth.php.twig');
        }

        $name = $_POST['name'] ?? null;
        $password = $_POST['password'] ?? null;

        $errors = [];

        if(!$name || !$password){
            $errors[] = 'Введите данные для авторизации';
        }

        if (!$this->repository->checkName((string)$name)) {
            $errors[] = 'Имя не верное !';
        }

        if (!$this->repository->checkPassword((string)$password)) {
            $errors[] = 'Пароль не может быть менее 3-х символов !';

            return $this->twig->render('admin/auth.php.twig',[
                'errors' => $errors
            ]);
        }

        $admin = new Admin((string)$name, (string)$password);
        $adminId = $this->repository->checkAdminData($admin);

        if (!$adminId){
            $errors[] = 'Неверное имя или пароль !';
            return $this->twig->render('admin/auth.php.twig',[
                'errors' => $errors
            ]);
        }
            $this->repository->auth($adminId);
            header("Location: /");
            return '';
    }
}