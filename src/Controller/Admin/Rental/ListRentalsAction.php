<?php

declare(strict_types=1);

namespace App\Controller\Admin\Rental;

use App\Controller\BaseController;
use App\Form\Rental\RentalFilterType;
use App\Form\Rental\RentalType;
use App\Repository\Rental\RentalRepository;
use App\Service\Pager\Pager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/admin/rentals") */
class ListRentalsAction extends BaseController
{
    private RentalRepository $rentalRepository;
    private Pager $pager;

    public function __construct(RentalRepository $rentalRepository, Pager $pager)
    {
        $this->rentalRepository = $rentalRepository;
        $this->pager = $pager;
    }

    /** @Route("/", name="admin_rentals_list", methods={"GET"}) */
    public function __invoke(Request $request): Response
    {
        $qb = $this->rentalRepository->findAllQueryBuilder();
        $rentalsFilterForm = $this->createForm(RentalFilterType::class);
        $rentalsFilterForm->handleRequest($request);
        if ($rentalsFilterForm->isSubmitted() && $rentalsFilterForm->isValid()) {
            $this->rentalRepository->addWhere($rentalsFilterForm->getData(), $qb);
        }
        $pager = $this->pager->getPager($qb, (int) $request->query->get('page', 1));

        return $this->render('admin/rental/list.html.twig', [
            'rentalsFilterForm' => $rentalsFilterForm->createView(),
            'rentals' => $pager->getCurrentPageResults(),
            'pager' => $pager,
        ]);
    }
}