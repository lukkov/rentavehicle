<?php

declare(strict_types=1);

namespace App\Repository\Car;

use App\Entity\Car\Brand\Brand;
use App\Entity\Car\Car;
use App\Entity\Car\Feature\Feature;
use App\Entity\Car\Model\Model;
use App\Entity\Salon\Salon;
use App\Repository\BaseRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;
use \DateTime;

/**
 * @method Car|null find($id, $lockMode = null, $lockVersion = null)
 * @method Car|null findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findAll()
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends ServiceEntityRepository
{
    use BaseRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    public function findAllQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('car');
    }

    public function findByOwnerQueryBuilder(UserInterface $user): QueryBuilder
    {
        return $this->findAllQueryBuilder()
            ->join('car.salon', 'salon')
            ->andWhere('salon.owner = :owner')->setParameter('owner', $user);
    }

    public function addWhere(array $params, QueryBuilder $qb): void
    {
        if (isset($params['salon']) && $params['salon']) {
            /** @var Salon $salon */
            $salon = $params['salon'];
            $qb->andWhere('car.salon = :salon')->setParameter('salon', $salon);
        }

        if (isset($params['brand']) && $params['brand']) {
            /** @var Brand $model */
            $brand = $params['brand'];
            $qb->andWhere('car.brand = :brand')->setParameter('brand', $brand);
        }

        if (isset($params['model']) && $params['model']) {
            /** @var Model $model */
            $model = $params['model'];
            $qb->andWhere('car.model = :model')->setParameter('model', $model);
        }

        if (isset($params['type']) && $params['type']) {
            $qb->andWhere('car.type = :type')->setParameter('type', $params['type']);
        }

        if (isset($params['color']) && $params['color']) {
            $qb->andWhere('car.color = :color')->setParameter('color', $params['color']);
        }

        if (isset($params['fuel']) && $params['fuel']) {
            $qb->andWhere('car.fuel = :fuel')->setParameter('fuel', $params['fuel']);
        }

        if (isset($params['transmission']) && $params['transmission']) {
            $qb->andWhere('car.transmission = :transmission')
                ->setParameter('transmission', $params['transmission']);
        }

        if (isset($params['numberOfDoors']) && $params['numberOfDoors']) {
            $qb->andWhere('car.numberOfDoors = :numberOfDoors')
                ->setParameter('numberOfDoors', $params['numberOfDoors']);
        }

        if (isset($params['numberOfSeats']) && $params['numberOfSeats']) {
            $qb->andWhere('car.numberOfSeats = :numberOfSeats')
                ->setParameter('numberOfSeats', $params['numberOfSeats']);
        }

        if (isset($params['pricePerDayFrom']) && $params['pricePerDayFrom']) {
            $qb->andWhere('car.pricePerDayFrom >= :pricePerDayFrom')
                ->setParameter('pricePerDayFrom', $params['pricePerDayFrom']);
        }

        if (isset($params['pricePerDayTo']) && $params['pricePerDayTo']) {
            $qb->andWhere('car.pricePerDayTo <= :pricePerDayTo')
                ->setParameter('pricePerDayTo', $params['pricePerDayTo']);
        }

        if (isset($params['featured']) && $params['featured']) {
            $qb->andWhere('car.featured = :featured')->setParameter('featured', true);
        }

        if (isset($params['city']) && $params['city']) {
            $qb->join('car.salon', 'salon')
                ->andWhere('salon.city = :city')->setParameter('city', $params['city']);
        }

        if (isset($params['features']) && $params['features']) {
            $qb->join('car.features', 'feature');
            /** @var Feature $feature */
            foreach ($params['features'] as $feature) {
                $qb->andWhere('car.features IN :feature')->setParameter('feature', $feature);
            }
        }

        /** @var DateTime|null $availableFrom */
        $availableFrom = isset($params['availableFrom']) ? $params['availableFrom'] : null;
        /** @var DateTime|null $availableTo */
        $availableTo = isset($params['availableTo']) ? $params['availableTo'] : null;

        if ($availableFrom || $availableTo) {
            $qb->andWhere("car.id NOT IN (". $this->getExcludedCarIdsDql(). ")")
                ->setParameter('startDate', $availableFrom)
                ->setParameter('endDate', $availableTo);
        }
    }

    /** @return Car[] */
    public function findFeaturedCars(): iterable
    {
        return $this->findAllQueryBuilder()
            ->andWhere('car.featured = :featured')
            ->setParameter('featured', true)
            ->getQuery()
            ->getResult();
    }

    public function getFeaturedCount(): int
    {
        return (int) $this->findAllQueryBuilder()
            ->andWhere('car.featured = :featured')->setParameter('featured', true)
            ->select('count(car.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findCarsBySalonQueryBuilder(Salon $salon): QueryBuilder
    {
        return $this->findAllQueryBuilder()
            ->andWhere('car.salon = :salon')->setParameter('salon', $salon);
    }

    private function getExcludedCarIdsDql(): string
    {
        return $subQuery = <<<DQL
            SELECT IDENTITY(rental.car) FROM App\Entity\Rental\Rental rental 
            WHERE rental.startDate <= :startDate AND rental.endDate >= :endDate
            OR rental.startDate >= :startDate AND rental.endDate <= :endDate
            OR rental.startDate >= :startDate AND rental.endDate >= :endDate AND rental.startDate <= :endDate
            OR rental.startDate <= :startDate AND rental.endDate <= :endDate AND rental.endDate >= :startDate 
        DQL;
    }
}
