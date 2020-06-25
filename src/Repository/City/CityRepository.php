<?php

declare(strict_types=1);

namespace App\Repository\City;

use App\Entity\City\City;
use App\Repository\BaseRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, array $orderBy = null)
 * @method City[]    findAll()
 * @method City[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends ServiceEntityRepository
{
    use BaseRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }

    public function findAllQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('city');
    }

    public function addWhere(array $params, QueryBuilder $qb): void
    {
        if (isset($params['name']) && $params['name']) {
            $qb->andWhere('city.name LIKE :name')->setParameter('name', '%'.$params['name'].'%');
        }

        if (isset($params['postalCode']) && $params['postalCode']) {
            $qb->andWhere('city.postalCode LIKE :postalCode')->setParameter('postalCode', '%'.$params['postalCode'].'%');
        }
    }
}