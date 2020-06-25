<?php

declare(strict_types=1);

namespace App\Repository\Car\Brand;

use App\Entity\Car\Brand\Brand;
use App\Repository\BaseRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Brand|null find($id, $lockMode = null, $lockVersion = null)
 * @method Brand|null findOneBy(array $criteria, array $orderBy = null)
 * @method Brand[]    findAll()
 * @method Brand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrandRepository extends ServiceEntityRepository
{
    use BaseRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Brand::class);
    }

    public function findAllQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('brand');
    }

    public function addWhere(array $params, QueryBuilder $qb): void
    {
        if (isset($params['name']) && $params['name']) {
            $qb->andWhere('brand.name LIKE :name')->setParameter('name', '%'.$params['name'].'%');
        }
    }
}