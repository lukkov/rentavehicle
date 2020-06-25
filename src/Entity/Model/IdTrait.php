<?php

declare(strict_types=1);

namespace App\Entity\Model;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Doctrine\ORM\Mapping as ORM;

trait IdTrait
{
    /**
     * @psalm-suppress PropertyNotSetInConstructor
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private UuidInterface $id;

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }
}