<?php

declare(strict_types=1);

namespace App\Controller\Front\Profile\Car;

use App\Controller\BaseController;
use App\Entity\Car\Car;
use App\Repository\Car\CarRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/profile/cars") */
class DeleteCarAction extends BaseController
{
    private CarRepository $carRepository;

    public function __construct(CarRepository $carRepository)
    {
        $this->carRepository = $carRepository;
    }

    /** @Route("/delete/{id}", name="profile_cars_delete", methods={"GET", "POST"}) */
    public function __invoke(Car $car): Response
    {
        $this->carRepository->delete($car);
        $this->addDeletedFlash();

        return $this->redirectToRoute('profile_cars_list');
    }
}