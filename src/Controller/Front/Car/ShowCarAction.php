<?php

declare(strict_types=1);

namespace App\Controller\Front\Car;

use App\Controller\BaseController;
use App\Entity\Car\Car;
use App\Form\Rental\RentCarType;
use App\Repository\Car\CarRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowCarAction extends BaseController
{
    private CarRepository $carRepository;

    public function __construct(CarRepository $carRepository)
    {
        $this->carRepository = $carRepository;
    }

    /** @Route("/cars/{id}", name="cars_show", methods={"GET", "POST"}) */
    public function __invoke(Car $car, Request $request): Response
    {
        $form = $this->createForm(RentCarType::class, null, ['car' => $car]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $rental = $form->getData()) {
            $this->carRepository->save($rental);

            return $this->redirectToRoute('profile_my_rentals_list');
        }

        return $this->render('front/cars/show.html.twig', [
            'rentalForm' => $form->createView(),
            'car'        => $car,
        ]);
    }
}
