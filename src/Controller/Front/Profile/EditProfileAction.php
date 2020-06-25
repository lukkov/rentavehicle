<?php

declare(strict_types=1);

namespace App\Controller\Front\Profile;

use App\Controller\BaseController;
use App\Entity\User\User;
use App\Form\User\UserProfileType;
use App\Repository\User\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditProfileAction extends BaseController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /** @Route("/profile/update/{id}", name="profile_update", methods={"GET", "POST"}) */
    public function __invoke(Request $request, User $user): Response
    {
        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository->flush();
            $this->addUpdatedFlash();
        }

        return $this->render('front/profile/profile_form_view.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}