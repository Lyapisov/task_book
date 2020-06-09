<?php

declare(strict_types=1);


namespace TaskManager\Repository;

use TaskManager\Model\Sort;
use TaskManager\Services\Db;
use PDO;
use TaskManager\Model\Task;
use ElisDN\Hydrator\Hydrator;

final class TaskRepository
{
    private PDO $pdo;
    private Hydrator $hydrator;

    /**
     * TaskRepository constructor.
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->hydrator = new Hydrator();
    }

    public function getAllByFilter(string $choice, int $page, int $taskPerPage):array
    {
        $sortParam = $this->getSortParameters($choice);
        $page = intval($page);
        $offset = ($page - 1) * $taskPerPage;
        $nameSortParam = $sortParam->getNameSortParam();
        $sortingDirection = $sortParam->getSortingDirection();

        $sql = 'SELECT id, name, user_name, user_email, completed, edited FROM task '
            . 'ORDER BY ' . $nameSortParam . ' ' . $sortingDirection
            . ' LIMIT ' . $taskPerPage
            . ' OFFSET ' . $offset;
        $result = $this->pdo->query($sql);
        $tasks = [];

        while ($row = $result->fetch())
        {
            $tasks[] = $this->hydrateTask($row);
        }
        return $tasks;
    }

    public function add(Task $task):void
    {
        $taskData = $this->extractTaskData($task);

        $sql = 'INSERT INTO task '
            . '(name, user_name, user_email, completed, edited) '
            . 'VALUES '
            . '(:name, :user_name, :user_email, :completed, :edited)';

        $result = $this->pdo->prepare($sql);
        $result->bindParam(':name', $taskData['name'], PDO::PARAM_STR);
        $result->bindParam(':user_name', $taskData['userName'], PDO::PARAM_STR);
        $result->bindParam(':user_email', $taskData['userEmail'], PDO::PARAM_STR);
        $result->bindParam(':completed', $taskData['completed'], PDO::PARAM_BOOL);
        $result->bindParam(':edited', $taskData['edited'], PDO::PARAM_BOOL);
        $result->execute();
    }

    public function updateTaskById(Task $task):void
    {

        $taskData = $this->extractTaskData($task);

        $sql = "UPDATE task
            SET 
                name = :name, 
                user_name = :user_name, 
                user_email = :user_email, 
                completed = :completed, 
                edited = :edited, 
            WHERE id = :id";

        $result = $this->pdo->prepare($sql);
        $result->bindParam(':id', $taskData['id'],PDO::PARAM_INT);
        $result->bindParam(':name', $taskData['name'], PDO::PARAM_STR);
        $result->bindParam(':user_name', $taskData['userName'], PDO::PARAM_STR);
        $result->bindParam(':user_email', $taskData['userEmail'], PDO::PARAM_STR);
        $result->bindParam(':completed', $taskData['completed'], PDO::PARAM_BOOL);
        $result->bindParam(':edited', $taskData['edited'], PDO::PARAM_BOOL);
        $result->execute();
    }

    public function updateCompleted(int $id):void
    {
        $task = $this->getTaskById($id);
        $taskArray = $this->extractTaskData($task);
        $taskArray['completed'] = true;
        $task = $this->hydrateTask($taskArray);
        $this->updateTaskById($task);
    }

    public function getTaskById(int $id):Task
    {
        $sql = 'SELECT * FROM task WHERE id = :id';
        // Используется подготовленный запрос
        $result =$this->pdo->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        // Выполняем запрос
        $result->execute();
        // Возвращаем данные
        $row = $result->fetch();
        return $this->hydrateTask($row);
    }

    private static function create(string $choice)
    {
        $sortParam = [
            'nameSortParam' => 'id',
            'sortingDirection' => 'DESC',
        ];


        if ($choice == 'newTask') {
            return $sortParam;
        }
        if ($choice == 'nameTaskASC'){
            $sortParam['nameSortParam'] = 'name';
            $sortParam['sortingDirection'] = 'ASC';
            return $sortParam;
        }
        if ($choice == 'nameTaskDESC'){
            $sortParam['nameSortParam'] = 'name';
            $sortParam['sortingDirection'] = 'DESC';
            return $sortParam;
        }
        if ($choice == 'emailTaskASC'){
            $sortParam['nameSortParam'] = 'user_email';
            $sortParam['sortingDirection'] = 'ASC';
            return $sortParam;
        }
        if ($choice == 'emailTaskDESC'){
            $sortParam['nameSortParam'] = 'user_email';
            $sortParam['sortingDirection'] = 'DESC';
            return $sortParam;
        }
        if ($choice == 'completedTask'){
            $sortParam['nameSortParam'] = 'completed';
            $sortParam['sortingDirection'] = 'ASC';
            return $sortParam;
        }
        if ($choice == 'notCompletedTask'){
            $sortParam['nameSortParam'] = 'completed';
            $sortParam['sortingDirection'] = 'DESC';
            return $sortParam;
        }
        return $sortParam;
    }

    private function getSortParameters(string $choice): Sort
    {
        $params = self::create($choice);
        $nameSortParam = $params['nameSortParam'];
        $sortingDirection = $params['sortingDirection'];

        return new Sort($nameSortParam, $sortingDirection);
    }

    public function checkName(string $name):bool
    {
        if (!isset($name) || !empty($name)) {
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

    private function hydrateTask(array $row):Task
    {
        return $this->hydrator->hydrate(Task::class,
            [
                'id' => $row['id'],
                'name' => $row['name'],
                'userName' => $row['user_name'],
                'userEmail' => $row['user_email'],
                'completed' => $row['completed'],
                'edited' => $row['edited'],
            ]
        );
    }

    private function extractTaskData(Task $task):array
    {
        return $this->hydrator->extract($task, [
            'name',
            'userName',
            'userEmail',
            'completed',
            'edited'
        ]);
    }
}