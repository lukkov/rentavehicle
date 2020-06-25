<?php

declare(strict_types=1);

namespace App\Controller\Front\Profile\Rental;

use App\Controller\BaseController;
use App\Entity\Rental\Rental;
use App\Form\Rental\RentalType;
use App\Repository\Rental\RentalRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/profile/rentals") */
class UpdateRentalAction extends BaseController
{
    private RentalRepository $rentalRepository;

    public function __construct(RentalRepository $rentalRepository)
    {
        $this->rentalRepository = $rentalRepository;
    }

    /** @Route("/update/{id}", name="profile_rentals_update", methods={"GET", "POST"}) */
    public function __invoke(Request $request, Rental $rental): Response
    {
        $form = $this->createForm(RentalType::class, $rental);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->rentalRepository->flush();
            $this->addUpdatedFlash();

            return $this->redirectToRoute('profile_rentals_list');
        }

        return $this->render('front/profile/profile_form_view.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}