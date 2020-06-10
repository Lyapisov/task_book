<?php

declare(strict_types=1);


namespace TaskManager\Model;


final class Sort
{
    private string $nameSortParam = 'id';
    private string $sortingDirection = 'DESC';

    /**
     * Sort constructor.
     * @param string $nameSortParam
     * @param string $sortingDirection
     */
    public function __construct(string $nameSortParam, string $sortingDirection)
    {
        $this->nameSortParam = $nameSortParam;
        $this->sortingDirection = $sortingDirection;
    }

    /**
     * @return string
     */
    public function getNameSortParam(): string
    {
        return $this->nameSortParam;
    }

    /**
     * @return string
     */
    public function getSortingDirection(): string
    {
        return $this->sortingDirection;
    }
}