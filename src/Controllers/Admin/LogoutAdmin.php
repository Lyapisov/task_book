<?php

declare(strict_types=1);


namespace TaskManager\Controllers\Admin;


final class LogoutAdmin
{
    public function __invoke()
    {
        unset($_SESSION['admin']);
        header("Location: /");
    }
}