<?php

declare(strict_types=1);

namespace App\Controller\Front\Profile\Rental;

use App\Controller\BaseController;
use App\Repository\Rental\RentalRepository;
use App\Service\Pager\Pager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/profile/my-rentals") */
class ListMyRentalsAction extends BaseController
{
    private RentalRepository $rentalRepository;
    private Pager $pager;

    public function __construct(RentalRepository $rentalRepository, Pager $pager)
    {
        $this->rentalRepository = $rentalRepository;
        $this->pager = $pager;
    }

    /** @Route("/", name="profile_my_rentals_list", methods={"GET"}) */
    public function __invoke(Request $request): Response
    {
        $qb = $this->rentalRepository->findByUserQueryBuilder($this->getUser());
        $pager = $this->pager->getPager($qb, (int) $request->query->get('page', 1));

        return $this->render('front/profile/rental/list_my.html.twig', [
            'rentals' => $pager->getCurrentPageResults(),
            'pager' => $pager,
        ]);
    }
}