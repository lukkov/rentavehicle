<?php

declare(strict_types=1);

namespace App\Service\Pager;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\PagerfantaInterface;

class Pager
{
    public function getPager(QueryBuilder $qb, int $currentPage, ?int $itemsPerPage = 10): PagerfantaInterface
    {
        return (new Pagerfanta(new QueryAdapter($qb)))->setMaxPerPage($itemsPerPage)->setCurrentPage($currentPage);
    }
}