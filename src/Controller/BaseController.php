<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    protected function getUser(): User
    {
        /** @var User $user */
        $user = parent::getUser();

        return $user;
    }

    protected function addUpdatedFlash(): void
    {
        $this->addFlash('success', 'Record updated successfully!');
    }

    protected function addCreatedFlash()
    {
        $this->addFlash('success', 'Record created successfully!');
    }

    protected function addDeletedFlash()
    {
        $this->addFlash('success', 'Record deleted successfully!');
    }
}