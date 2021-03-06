<?php

declare(strict_types=1);

namespace App\Controller\Admin\Rental;

use App\Controller\BaseController;
use App\Entity\Rental\Rental;
use App\Repository\Rental\RentalRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/admin/rentals") */
class DeleteRentalAction extends BaseController
{
    private RentalRepository $rentalRepository;

    public function __construct(RentalRepository $rentalRepository)
    {
        $this->rentalRepository = $rentalRepository;
    }

    /** @Route("/delete/{id}", name="admin_rentals_delete", methods={"GET", "POST"}) */
    public function __invoke(Rental $rental): Response
    {
        $this->rentalRepository->delete($rental);
        $this->addDeletedFlash();

        return $this->redirectToRoute('admin_rentals_list');
    }
}