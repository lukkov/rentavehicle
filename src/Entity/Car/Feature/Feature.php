<?php

declare(strict_types=1);

namespace App\Entity\Car\Feature;

use App\Entity\Model\IdTrait;
use App\Entity\Model\Timestampable\TimestampableInterface;
use App\Entity\Model\Timestampable\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="feature")
 * @ORM\HasLifecycleCallbacks()
 */
class Feature implements TimestampableInterface
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