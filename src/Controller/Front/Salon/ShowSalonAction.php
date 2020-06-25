<?php

declare(strict_types=1);

namespace App\Controller\Front\Salon;

use App\Controller\BaseController;
use App\Entity\Salon\Salon;
use App\Repository\Car\CarRepository;
use App\Service\Pager\Pager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowSalonAction extends BaseController
{
    private const CARS_PER_PAGE = 3;

    private CarRepository $carRepository;
    private Pager $pager;

    public function __construct(CarRepository $carRepository, Pager $pager)
    {
        $this->carRepository = $carRepository;
        $this->pager = $pager;
    }

    /** @Route("/salons/{id}", name="salons_show", methods={"GET", "POST"}) */
    public function __invoke(Salon $salon, Request $request): Response
    {
        $salonCarsQb = $this->carRepository->findCarsBySalonQueryBuilder($salon);
        $pager = $this->pager->getPager($salonCarsQb, (int)$request->query->get('page', 1), self::CARS_PER_PAGE);

        return $this->render('front/salons/show.html.twig', [
            'salon' => $salon,
            'salonCars' => $pager->getCurrentPageResults(),
            'pager' => $pager,
        ]);
    }
}