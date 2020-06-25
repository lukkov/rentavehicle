<?php

declare(strict_types=1);

namespace App\Repository\Car\Model;

use App\Entity\Car\Brand\Brand;
use App\Entity\Car\Model\Model;
use App\Repository\BaseRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Model|null find($id, $lockMode = null, $lockVersion = null)
 * @method Model|null findOneBy(array $criteria, array $orderBy = null)
 * @method Model[]    findAll()
 * @method Model[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModelRepository extends ServiceEntityRepository
{
    use BaseRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Model::class);
    }

    public function findAllQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('model');
    }

    public function addWhere(array $params, QueryBuilder $qb): void
    {
        if (isset($params['name']) && $params['name']) {
            $qb->andWhere('model.name LIKE :name')->setParameter('name', '%'.$params['name'].'%');
        }

        if (isset($params['brand']) && $params['brand']) {
            /** @var Brand $brand */
            $brand = $params['brand'];
            $qb->andWhere('model.brand = :brand')->setParameter('brand', $brand);
        }
    }

    /** @return Model[] */
    public function findModelsByBrand(Brand $brand): iterable
    {
        return $this->findAllQueryBuilder()
            ->andWhere('model.brand = brand')->setParameter('brand', $brand)
            ->getQuery()
            ->getResult();
    }
}