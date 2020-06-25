<?php

declare(strict_types=1);

namespace App\Controller\Admin\Car;

use App\Controller\BaseController;
use App\Form\Car\AdminCarType;
use App\Repository\Car\CarRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/admin/cars") */
class CreateCarAction extends BaseController
{
    private CarRepository $carRepository;

    public function __construct(CarRepository $carRepository)
    {
        $this->carRepository = $carRepository;
    }

    /** @Route("/create", name="admin_cars_create", methods={"GET", "POST"}) */
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(AdminCarType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $request->headers->has('refresh-form')) {
            $form->clearErrors(true);

            return $this->render('admin/cars/car_plain_form.html.twig', [
                'form' => $form->createView(),
            ]);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $this->carRepository->save($form->getData());
            $this->addCreatedFlash();

            return $this->redirectToRoute('admin_cars_list');
        }

        return $this->render('admin/cars/car_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
