<?php

declare(strict_types=1);

namespace App\Repository\Rental;

use App\Entity\Car\Car;
use App\Entity\Rental\Rental;
use App\Repository\BaseRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;
use \DateTime;

/**
 * @method Rental|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rental|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rental[]    findAll()
 * @method Rental[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RentalRepository extends ServiceEntityRepository
{
    use BaseRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rental::class);
    }

    public function findAllQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('rental');
    }

    public function findByUserQueryBuilder(UserInterface $user): QueryBuilder
    {
        return $this->findAllQueryBuilder()
            ->andWhere('rental.user = :user')
            ->setParameter('user', $user);
    }

    public function isCarAvailableForPeriod(Car $car, DateTime $startDate, DateTime $endDate, ?Rental $rental = null): bool
    {
        $qb = $this->decorateBuilderWithPeriod($this->findAllQueryBuilder(), $startDate, $endDate)
            ->andWhere('rental.car = :car')
            ->setParameter('car', $car);
        if ($rental !== null) {
            $qb->andWhere('rental.id != :excludedRentalId')
                ->setParameter('excludedRentalId', $rental->getId());
        }

        return $qb->getQuery()->getResult() ? true : false;
    }

    public function decorateBuilderWithPeriod(
        QueryBuilder $queryBuilder,
        ?DateTime $startDate = null,
        ?DateTime $endDate = null
    ): QueryBuilder
    {
        return $queryBuilder
            ->where('rental.startDate <= :startDate AND rental.endDate >= :endDate')
            ->orWhere('rental.startDate >= :startDate AND rental.endDate <= :endDate')
            ->orWhere('rental.startDate >= :startDate AND rental.endDate >= :endDate AND rental.startDate <= :endDate')
            ->orWhere('rental.startDate <= :startDate AND rental.endDate <= :endDate AND rental.endDate >= :startDate')
            ->setParameters(['startDate'=> $startDate, 'endDate'  => $endDate]);
    }

    public function addWhere(array $params, QueryBuilder $qb): void
    {
        $startDate = isset($params['startDate']) ? $params['startDate'] : null;
        $endDate = isset($params['startDate']) ? $params['startDate'] : null;

        if ($startDate || $endDate) {
            $this->decorateBuilderWithPeriod($qb, $startDate, $endDate);
        }

        if (isset($params['salon']) && $params['salon']) {
            $qb->join('rental.car', 'rented_car')
                ->andWhere('rented_car.salon = :salon')->setParameter('salon', $params['salon']);
        }
    }

    /** @return Rental[] */
    public function findRentalsByCar(Car $car): iterable
    {
        return $this->findAllQueryBuilder()
            ->andWhere('rental.car = :car')->setParameter('car', $car)
            ->getQuery()
            ->getResult();
    }

    public function findByOwnerQueryBuilder(UserInterface $user): QueryBuilder
    {
        return $this->findAllQueryBuilder()
            ->join('rental.car', 'car')
            ->join('car.salon', 'salon')
            ->andWhere('salon.owner = :owner')->setParameter('owner', $user);
    }
}