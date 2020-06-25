<?php

declare(strict_types=1);

namespace App\Controller\Admin\User;

use App\Controller\BaseController;
use App\Entity\User\User;
use App\Repository\User\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/admin/users") */
class DeleteUserAction extends BaseController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /** @Route("/delete/{id}", name="admin_users_delete", methods={"GET"}) */
    public function __invoke(User $user): Response
    {
        $this->userRepository->delete($user);
        $this->addDeletedFlash();

        return $this->redirectToRoute('admin_users_list');
    }
}