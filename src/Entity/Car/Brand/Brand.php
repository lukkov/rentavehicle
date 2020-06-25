<?php

declare(strict_types=1);

namespace App\Entity\Car\Brand;

use App\Entity\Model\IdTrait;
use App\Entity\Model\Timestampable\TimestampableInterface;
use App\Entity\Model\Timestampable\TimestampableTrait;
use App\Repository\Car\Brand\BrandRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BrandRepository::class)
 * @ORM\Table(name="car_brand")
 * @ORM\HasLifecycleCallbacks()
 */
class Brand implements TimestampableInterface
{
    use IdTrait, TimestampableTrait {
        IdTrait::__construct as initId;
        TimestampableTrait::__construct as initTimestamps;
    }

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
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
}
