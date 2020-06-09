<?php

declare(strict_types=1);


namespace TaskManager\Repository;


use ElisDN\Hydrator\Hydrator;
use PDO;
use TaskManager\Model\Admin;

final class AdminRepository
{
    private PDO $pdo;
    private Hydrator $hydrator;

    /**
     * AdminRepository constructor.
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->hydrator = new Hydrator();
    }

    public function checkAdmin():bool
    {
        // Проверяем авторизирован ли пользователь. Если нет, он будет переадресован
        $adminId = self::checkLogged();
        // Получаем информацию о текущем пользователе
        $admin = self::getAdminById($adminId);
        // Если роль текущего пользователя "admin", пускаем его в админпанель
        if ($admin->getRole() == 'admin') {
            return true;
        }
        // Иначе завершаем работу с сообщением об закрытом доступе
        die('Доступ запрещен!');
    }

    public function checkName(string $name):bool
    {
        if (!isset($name) || !empty($name)) {
            return true;
        }
        return false;
    }

    public  function checkPassword(string $password):bool
    {
        if(strlen($password) >= 3){
            return true;
        }
        return false;
    }

    public static function checkEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)){
            return true;
        }
        return false;
    }

    public function checkAdminData(Admin $admin)
    {
        $adminData = $this->extractAdminData($admin);

        $sql = 'SELECT * FROM admin WHERE name=:name AND password=:password';

        $result = $this->pdo->prepare($sql);
        $result->bindParam(':name', $adminData['name'], PDO::PARAM_STR);
        $result->bindParam(':password', $adminData['password'], PDO::PARAM_STR);
        $result->execute();

        $row = $result->fetch();
        if (!$row){
            return false;
        }
        $admin = $this->hydrateAdmin($row);
        return $admin->getId();
    }

    public static function auth($adminId){
        $_SESSION['admin'] = $adminId;
    }

    public function isGuest():bool
    {
        if (!isset($_SESSION['admin'])){
            return false;
        }
            return true;
    }

    private function getAdminById(int $id):Admin
    {
        if($id) {
            $sql = 'SELECT * FROM admin WHERE id=:id';

            $result = $this->pdo->prepare($sql);
            $result->bindParam(':id', $id, PDO::PARAM_STR);

            $result->setFetchMode(PDO::FETCH_ASSOC);
            $result->execute();
            $row = $result->fetch();

            return $this->hydrateAdmin($row);
        }
    }

    private function checkLogged()
    {

        if (isset($_SESSION['admin'])){
            return $_SESSION['admin'];
        }
        header("Location: /auth");
    }

    private function hydrateAdmin(array $row):Admin
    {
        return $this->hydrator->hydrate(Admin::class,
            [
                'id' => $row['id'],
                'name' => $row['name'],
                'password' => $row['password'],
                'role' => $row['role'],
            ]
        );
    }

    private function extractAdminData(Admin $admin):array
    {
        return $this->hydrator->extract($admin, [
            'name',
            'password',
            'role'
        ]);
    }
}

