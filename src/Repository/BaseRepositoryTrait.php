<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityManager;

/**
 * @method EntityManager getEntityManager()
 */
trait BaseRepositoryTrait
{
    public function delete(object $entity, bool $flush = true): void
    {
        $em = $this->getEntityManager();
        $em->remove($entity);
        if ($flush) {
            $em->flush();
        }
    }

    public function save(object $entity, bool $flush = true): void
    {
        $em = $this->getEntityManager();
        $em->persist($entity);
        if ($flush) {
            $em->flush();
        }
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }
}