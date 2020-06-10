<?php

declare(strict_types=1);


namespace TaskManager\Services;


use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

final class Paginator
{

    private int $currentPage;
    private int $prevPage;
    private int $nextPage;
    private int $maxPerPage;
    private int $totalPages;
    private int $firstPage;
    private int $lastPage;
    private array $currentPageResults;

    /**
     * Paginator constructor.
     * @param int $currentPage
     * @param int $prevPage
     * @param int $nextPage
     * @param array $currentPageResults
     * @param int $maxPerPage
     * @param int $totalPages
     * @param int $firstPage
     * @param int $lastPage
     */
    private function __construct(int $currentPage, int $prevPage, int $nextPage, array $currentPageResults, int $maxPerPage, int $totalPages, int $firstPage, int $lastPage)
    {
        $this->currentPageResults = $currentPageResults;
        $this->currentPage = $currentPage;
        $this->prevPage = $prevPage;
        $this->nextPage = $nextPage;
        $this->maxPerPage = $maxPerPage;
        $this->totalPages = $totalPages;
        $this->firstPage = $firstPage;
        $this->lastPage = $lastPage;
    }

    public static function paginate(array $rows, int $currentPage, int $maxPerPage): Paginator
    {
        $adapter = new ArrayAdapter($rows);
        $pagerfanta = new Pagerfanta($adapter);

        $pagerfanta->setMaxPerPage($maxPerPage); // 10 by default
        $maxPerPage = $pagerfanta->getMaxPerPage();

        $pagerfanta->setCurrentPage($currentPage); // 1 by default
        $currentPage = $pagerfanta->getCurrentPage();

        $nbResults = $pagerfanta->getNbResults();
        $currentPageResults = $pagerfanta->getCurrentPageResults();
        $results =[];
        foreach ($currentPageResults as $result){
            $results[] = $result;
        }
        return new Paginator(
            $currentPage,
            $pagerfanta->hasPreviousPage()? $pagerfanta->getPreviousPage(): $currentPage,
            $pagerfanta->hasNextPage()? $pagerfanta->getNextPage(): $currentPage,
            $results,
            $maxPerPage,
            $pagerfanta->getNbPages(),
            1,
            $pagerfanta->getNbPages());
    }

    /**
     * @return array
     */
    public function getCurrentPageResults(): array
    {
        return $this->currentPageResults;
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @return int
     */
    public function getPrevPage(): int
    {
        return $this->prevPage;
    }

    /**
     * @return int
     */
    public function getNextPage(): int
    {
        return $this->nextPage;
    }

    /**
     * @return int
     */
    public function getMaxPerPage(): int
    {
        return $this->maxPerPage;
    }

    /**
     * @return int
     */
    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    /**
     * @return int
     */
    public function getFirstPage(): int
    {
        return $this->firstPage;
    }

    /**
     * @return int
     */
    public function getLastPage(): int
    {
        return $this->lastPage;
    }
}