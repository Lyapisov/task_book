<?php

declare(strict_types=1);


namespace TaskManager\Repository;

use TaskManager\Model\Sort;
use TaskManager\Services\Db;
use PDO;
use TaskManager\Model\Task;
use ElisDN\Hydrator\Hydrator;
use TaskManager\Services\Paginator;

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

    public function getAllByFilter(string $choice, int $page, int $taskPerPage):Paginator
    {
        $sortParam = $this->getSortParameters($choice);
        $nameSortParam = $sortParam->getNameSortParam();
        $sortingDirection = $sortParam->getSortingDirection();

        $sql = 'SELECT id, name, user_name, user_email, completed, edited FROM task '
            . 'ORDER BY ' . $nameSortParam . ' ' . $sortingDirection;
        $result = $this->pdo->query($sql);
        $tasks = [];

        while ($row = $result->fetch())
        {
            $tasks[] = $this->hydrateTask($row);
        }
        return Paginator::paginate($tasks, $page, $taskPerPage);
    }

    public function add(Task $task):void
    {
        $taskData = $this->extractTaskDataByAdd($task);

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

    public function update(Task $task):void
    {
        $taskData = $this->extractTaskData($task);

        $sql = "UPDATE task
            SET 
                name = :name, 
                user_name = :user_name, 
                user_email = :user_email, 
                completed = :completed, 
                edited = :edited 
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

    private static function parseSortParam(string $choice):array
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
            $sortParam['sortingDirection'] = 'DESC';
            return $sortParam;
        }
        if ($choice == 'notCompletedTask'){
            $sortParam['nameSortParam'] = 'completed';
            $sortParam['sortingDirection'] = 'ASC';
            return $sortParam;
        }
        return $sortParam;
    }

    public function getSortParameters(string $choice): Sort
    {
        $params = self::parseSortParam($choice);
        $nameSortParam = $params['nameSortParam'];
        $sortingDirection = $params['sortingDirection'];

        return new Sort($nameSortParam, $sortingDirection);
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
            'id',
            'name',
            'userName',
            'userEmail',
            'completed',
            'edited'
        ]);
    }
    private function extractTaskDataByAdd(Task $task):array
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