<?php

declare(strict_types=1);

namespace App\Entity\Car\Model;

use App\Entity\Car\Brand\Brand;
use App\Entity\Model\IdTrait;
use App\Entity\Model\Timestampable\TimestampableInterface;
use App\Entity\Model\Timestampable\TimestampableTrait;
use App\Repository\Car\Model\ModelRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ModelRepository::class)
 * @ORM\Table(name="car_model")
 * @ORM\HasLifecycleCallbacks()
 */
class Model implements TimestampableInterface
{
    use IdTrait, TimestampableTrait {
        IdTrait::__construct as initId;
        TimestampableTrait::__construct as initTimestamps;
    }

    /**
     * @ORM\ManyToOne(targetEntity=Brand::class)
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private Brand $brand;

    /**
     * @ORM\Column(type="string")
     */
    private string $name;

    public function __construct(Brand $brand, string $name)
    {
        $this->brand = $brand;
        $this->name = $name;
        $this->initId();
        $this->initTimestamps();
    }

    public function getBrand(): Brand
    {
        return $this->brand;
    }

    public function setBrand(Brand $brand): void
    {
        $this->brand = $brand;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
