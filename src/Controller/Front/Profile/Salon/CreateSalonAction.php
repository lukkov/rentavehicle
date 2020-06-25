<?php

declare(strict_types=1);

namespace App\Controller\Front\Profile\Salon;

use App\Controller\BaseController;
use App\Form\Salon\SalonType;
use App\Repository\Salon\SalonRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/profile/salon") */
class CreateSalonAction extends BaseController
{
    private SalonRepository $salonRepository;

    public function __construct(SalonRepository $salonRepository)
    {
        $this->salonRepository = $salonRepository;
    }

    /** @Route("/create", name="profile_salons_create", methods={"GET", "POST"}) */
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(SalonType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->salonRepository->save($form->getData());
            $this->addCreatedFlash();

            return $this->redirectToRoute('profile_salons_list');
        }

        return $this->render('front/profile/profile_form_view.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}