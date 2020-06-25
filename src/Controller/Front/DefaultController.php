<?php

declare(strict_types=1);

namespace App\Controller\Front;

use App\Controller\BaseController;
use App\Form\Car\CarFilterType;
use App\Repository\Car\CarRepository;
use App\Repository\Salon\SalonRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends BaseController
{
    private CarRepository $carRepository;
    private SalonRepository $salonRepository;

    public function __construct(CarRepository $carRepository, SalonRepository $salonRepository)
    {
        $this->carRepository = $carRepository;
        $this->salonRepository = $salonRepository;
    }

    /** @Route("/", name="index") */
    public function index(): Response
    {
        $quickSearchForm = $this->createForm(
            CarFilterType::class,
            null,
            ['action' => $this->generateUrl('cars_list'), 'method' => 'GET', 'attr' => ['class' => 'trip-form']])
        ;

        return $this->render('front/index/index.html.twig', [
            'quickSearchForm' => $quickSearchForm->createView(),
            'featuredCars' => $this->carRepository->findFeaturedCars(),
            'latestSalon' => $this->salonRepository->findLatestSalon(),
        ]);
    }
}