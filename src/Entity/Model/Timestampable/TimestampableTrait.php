<?php

declare(strict_types=1);

namespace App\Entity\Model\Timestampable;

use Doctrine\ORM\Mapping as ORM;
use \DateTime;

trait TimestampableTrait
{
    /**
     * @psalm-suppress PropertyNotSetInConstructor
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    private DateTime $createdAt;

    /**
     * @psalm-suppress PropertyNotSetInConstructor
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    private DateTime $updatedAt;

    public function __construct()
    {
        $this->createdAt = new DateTime('');
        $this->updatedAt = new DateTime();
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PreUpdate()
     */
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new DateTime();
    }
}