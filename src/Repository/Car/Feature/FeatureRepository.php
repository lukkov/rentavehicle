<?php

declare(strict_types=1);

namespace App\Repository\Car\Feature;

use App\Entity\Car\Feature\Feature;
use App\Repository\BaseRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Feature|null find($id, $lockMode = null, $lockVersion = null)
 * @method Feature|null findOneBy(array $criteria, array $orderBy = null)
 * @method Feature[]    findAll()
 * @method Feature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeatureRepository extends ServiceEntityRepository
{
    use BaseRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Feature::class);
    }

    public function findAllQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('feature');
    }

    public function addWhere(array $params, QueryBuilder $qb): void
    {
        if (isset($params['name']) && $params['name']) {
            $qb->andWhere('feature.name LIKE :name')->setParameter('name', '%'.$params['name'].'%');
        }
    }
}