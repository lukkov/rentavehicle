<?php

declare(strict_types=1);

namespace App\Controller\Admin\User;

use App\Controller\BaseController;
use App\Entity\User\User;
use App\Form\User\UserType;
use App\Repository\User\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/admin/users") */
class UpdateUserAction extends BaseController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /** @Route("/update/{id}", name="admin_users_update", methods={"GET", "POST"}) */
    public function __invoke(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository->flush();
            $this->addUpdatedFlash();

            return $this->redirectToRoute('admin_users_list');
        }

        return $this->render('admin/_common/form_view.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}