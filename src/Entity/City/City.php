<?php

declare(strict_types=1);

namespace App\Entity\City;

use App\Entity\Model\IdTrait;
use App\Entity\Model\Timestampable\TimestampableInterface;
use App\Entity\Model\Timestampable\TimestampableTrait;
use App\Repository\City\CityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CityRepository::class)
 * @ORM\Table(name="city")
 * @ORM\HasLifecycleCallbacks()
 */
class City implements TimestampableInterface
{
    use IdTrait, TimestampableTrait {
        IdTrait::__construct as initId;
        TimestampableTrait::__construct as initTimestamps;
    }

    /**
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @ORM\Column(type="integer")
     */
    private int $postalCode;

    public function __construct(string $name, int $postalCode)
    {
        $this->name = $name;
        $this->postalCode = $postalCode;
        $this->initId();
        $this->initTimestamps();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPostalCode(): int
    {
        return $this->postalCode;
    }

    public function setPostalCode(int $postalCode): void
    {
        $this->postalCode = $postalCode;
    }
}