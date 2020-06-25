<?php

declare(strict_types=1);

namespace App\Entity\Model\Timestampable;

use \DateTime;

interface TimestampableInterface
{
    public function getCreatedAt(): DateTime;

    public function getUpdatedAt(): DateTime;
}