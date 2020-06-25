<?php

declare(strict_types=1);

namespace App\Controller\Admin\Car;

use App\Controller\BaseController;
use App\Entity\Car\Car;
use App\Form\Car\AdminCarType;
use App\Repository\Car\CarRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/admin/cars") */
class UpdateCarAction extends BaseController
{
    private CarRepository $carRepository;

    public function __construct(CarRepository $carRepository)
    {
        $this->carRepository = $carRepository;
    }

    /** @Route("/update/{id}", name="admin_cars_update", methods={"GET", "POST"}) */
    public function __invoke(Request $request, Car $car): Response
    {
        $form = $this->createForm(AdminCarType::class, $car);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $request->headers->has('refresh-form')) {
            $form->clearErrors(true);

            return $this->render('admin/cars/car_plain_form.html.twig', [
                'form' => $form->createView(),
            ]);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $this->carRepository->flush();
            $this->addUpdatedFlash();

            return $this->redirectToRoute('admin_cars_list');
        }

        return $this->render('admin/cars/car_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
