<?php

namespace PickIt\Entities;

class Paginator
{
    private int $total;
    private int $perPage;
    private int $page;
    private int $totalPages;

    public function __construct (int $total, int $perPage, int $page, $totalPages) {
        $this->total = $total;
        $this->perPage = $perPage;
        $this->page = $page;
        $this->totalPages = $totalPages;
    }

    public function getTotal () : int {
        return $this->total;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getTotalPages(): int
    {
        return $this->totalPages;
    }
}