<?php

declare(strict_types=1);

namespace TaskManager\Model;

class Task
{
    private int $id;

    private string $name;

    private string $userName;

    private string $userEmail;

    private bool $completed = false;

    private bool $edited = false;

    /**
     * Task constructor.
     * @param string $name
     * @param string $userName
     * @param string $userEmail
     */
    public function __construct(
        string $name,
        string $userName,
        string $userEmail
    )
    {
        $this->name = $name;
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->completed = false;
        $this->edited = false;
    }

    public function edit(string $name):void
    {
        if(!$name){
            throw new \DomainException('Введите задачу !');
        }
        if($this->getName() == $name){
            return ;
        }
        $this->name = $name;
        $this->edited = true;
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @return string
     */
    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    /**
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->completed;
    }

    /**
     * @return bool
     */
    public function isEdited(): bool
    {
        return $this->edited;
    }

}