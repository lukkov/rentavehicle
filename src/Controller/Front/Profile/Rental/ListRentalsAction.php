<?php

declare(strict_types=1);

namespace App\Controller\Front\Profile\Rental;

use App\Controller\BaseController;
use App\Form\Rental\RentalFilterType;
use App\Repository\Rental\RentalRepository;
use App\Service\Pager\Pager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/profile/rentals") */
class ListRentalsAction extends BaseController
{
    private RentalRepository $rentalRepository;
    private Pager $pager;

    public function __construct(RentalRepository $rentalRepository, Pager $pager)
    {
        $this->rentalRepository = $rentalRepository;
        $this->pager = $pager;
    }

    /** @Route("/", name="profile_rentals_list", methods={"GET"}) */
    public function __invoke(Request $request): Response
    {
        $qb = $this->rentalRepository->findByOwnerQueryBuilder($this->getUser());
        $rentalsFilterForm = $this->createForm(RentalFilterType::class);
        $rentalsFilterForm->handleRequest($request);
        if ($rentalsFilterForm->isSubmitted() && $rentalsFilterForm->isValid()) {
            $this->rentalRepository->addWhere($rentalsFilterForm->getData(), $qb);
        }
        $pager = $this->pager->getPager($qb, (int) $request->query->get('page', 1));

        return $this->render('front/profile/rental/list.html.twig', [
            'rentalsFilterForm' => $rentalsFilterForm->createView(),
            'rentals' => $pager->getCurrentPageResults(),
            'pager' => $pager,
        ]);
    }
}