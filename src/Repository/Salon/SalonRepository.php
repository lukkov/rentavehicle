<?php

declare(strict_types=1);

namespace App\Repository\Salon;

use App\Entity\City\City;
use App\Entity\Salon\Salon;
use App\Repository\BaseRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Salon|null find($id, $lockMode = null, $lockVersion = null)
 * @method Salon|null findOneBy(array $criteria, array $orderBy = null)
 * @method Salon[]    findAll()
 * @method Salon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SalonRepository extends ServiceEntityRepository
{
    use BaseRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Salon::class);
    }

    public function findAllQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('salon');
    }

    public function findByOwnerQueryBuilder(UserInterface $user): QueryBuilder
    {
        return $this->findAllQueryBuilder()
            ->andWhere('salon.owner = :owner')
            ->setParameter('owner', $user);
    }

    public function addWhere(array $params, QueryBuilder $qb): void
    {
        if (isset($params['name']) && $params['name']) {
            $qb->andWhere('salon.name LIKE :name')->setParameter('name', '%'.$params['name'].'%');
        }

        if (isset($params['city']) && $params['city']) {
            /** @var City $brand */
            $city = $params['city'];
            $qb->andWhere('salon.city = :city')->setParameter('city', $city);
        }
    }

    public function findLatestSalon(): Salon
    {
        return $this->findAllQueryBuilder()
            ->orderBy('salon.createdAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }
}