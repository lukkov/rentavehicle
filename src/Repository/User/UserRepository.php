<?php

namespace App\Repository\User;

use App\Entity\User\User;
use App\Repository\BaseRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    use BaseRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findAllQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('user');
    }

    public function addWhere(array $params, QueryBuilder $qb): void
    {
        if (isset($params['firstName']) && $params['firstName']) {
            $qb->andWhere('user.firstName LIKE :firstName')->setParameter('firstName', '%'.$params['firstName'].'%');
        }
        if (isset($params['lastName']) && $params['lastName']) {
            $qb->andWhere('user.lastName LIKE :lastName')->setParameter('lastName', '%'.$params['lastName'].'%');
        }
        if (isset($params['email']) && $params['email']) {
            $qb->andWhere('user.email LIKE :email')->setParameter('email', '%'.$params['email'].'%');
        }
        if (isset($params['role'])) {
            $qb->andWhere('user.roles LIKE :role')->setParameter('role', '%'.$params['role'].'%');
        }
    }
}
