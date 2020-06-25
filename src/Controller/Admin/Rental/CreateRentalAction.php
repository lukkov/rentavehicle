<?php

declare(strict_types=1);

namespace App\Controller\Admin\Rental;

use App\Controller\BaseController;
use App\Form\Rental\RentalType;
use App\Repository\Rental\RentalRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/admin/rentals") */
class CreateRentalAction extends BaseController
{
    private RentalRepository $rentalRepository;

    public function __construct(RentalRepository $rentalRepository)
    {
        $this->rentalRepository = $rentalRepository;
    }

    /** @Route("/create", name="admin_rentals_create", methods={"GET", "POST"}) */
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(RentalType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->rentalRepository->save($form->getData());
            $this->addCreatedFlash();

            return $this->redirectToRoute('admin_rentals_list');
        }

        return $this->render('admin/_common/form_view.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}