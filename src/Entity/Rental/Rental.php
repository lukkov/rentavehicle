<?php

declare(strict_types=1);

namespace App\Entity\Rental;

use App\Entity\Car\Car;
use App\Entity\Model\IdTrait;
use App\Entity\Model\Timestampable\TimestampableInterface;
use App\Entity\Model\Timestampable\TimestampableTrait;
use App\Entity\User\User;
use App\Repository\Rental\RentalRepository;
use Doctrine\ORM\Mapping as ORM;
use \DateTime;

/**
 * @ORM\Entity(repositoryClass=RentalRepository::class)
 * @ORM\Table(name="rental")
 * @ORM\HasLifecycleCallbacks()
 */
class Rental implements TimestampableInterface
{
    use IdTrait, TimestampableTrait {
        IdTrait::__construct as initId;
        TimestampableTrait::__construct as initTimestamps;
    }

    /**
     * @ORM\ManyToOne(targetEntity=Car::class)
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private Car $car;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private User $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $endDate;

    /**
     * @ORM\Column(type="integer")
     */
    private int $price;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $payed;

    public function __construct(Car $car, User $user, DateTime $startDate, DateTime $endDate, bool $payed = false)
    {
        $this->car = $car;
        $this->user = $user;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->payed = $payed;
        $this->price = $this->calculatePrice($startDate, $endDate, $car->getPricePerDay());
        $this->initId();
        $this->initTimestamps();
    }

    public function getCar(): Car
    {
        return $this->car;
    }

    public function setCar(Car $car): void
    {
        $this->car = $car;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    public function isPayed(): bool
    {
        return $this->payed;
    }

    public function setPayed(bool $payed): void
    {
        $this->payed = $payed;
    }

    private function calculatePrice(DateTime $startDate, DateTime $endDate, int $pricePerDay): int
    {
        return $endDate->diff($startDate)->days * $pricePerDay;
    }
}