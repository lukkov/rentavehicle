<?php

declare(strict_types=1);

namespace App\Controller\Admin\Rental;

use App\Controller\BaseController;
use App\Entity\Rental\Rental;
use App\Form\Rental\RentalType;
use App\Repository\Rental\RentalRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/admin/rentals") */
class UpdateRentalAction extends BaseController
{
    private RentalRepository $rentalRepository;

    public function __construct(RentalRepository $rentalRepository)
    {
        $this->rentalRepository = $rentalRepository;
    }

    /** @Route("/update/{id}", name="admin_rentals_update", methods={"GET", "POST"}) */
    public function __invoke(Request $request, Rental $rental): Response
    {
        $form = $this->createForm(RentalType::class, $rental);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->rentalRepository->flush();
            $this->addUpdatedFlash();

            return $this->redirectToRoute('admin_rentals_list');
        }

        return $this->render('admin/_common/form_view.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}