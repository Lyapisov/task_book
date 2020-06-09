<?php

declare(strict_types=1);


namespace TaskManager\Model;


final class Admin
{
    private int $id;
    private string $name;
    private string $password;
    private string $role = 'user';

    /**
     * Admin constructor.
     * @param string $name
     * @param string $password
     */
    public function __construct(
        string $name,
        string $password
    ){
        $this->name = $name;
        $this->password = $password;
        $this->role = 'user';
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
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }
}