<?php

declare(strict_types=1);

namespace App\Controller\Front\Security;

use App\Controller\BaseController;
use App\Form\User\UserProfileType;
use App\Repository\User\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends BaseController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /** @Route("/registration", name="register", methods={"GET", "POST"}) */
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(UserProfileType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository->save($form->getData());
            $this->addFlash('success', 'Registration successful! Please sign in.');

            return $this->redirectToRoute('login');
        }
        return $this->render('front/security/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}