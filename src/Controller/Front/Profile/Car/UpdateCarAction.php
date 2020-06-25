<?php

declare(strict_types=1);

namespace App\Controller\Front\Profile\Car;

use App\Controller\BaseController;
use App\Entity\Car\Car;
use App\Form\Car\ProfileCarType;
use App\Repository\Car\CarRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/profile/cars") */
class UpdateCarAction extends BaseController
{
    private CarRepository $carRepository;

    public function __construct(CarRepository $carRepository)
    {
        $this->carRepository = $carRepository;
    }

    /** @Route("/update/{id}", name="profile_cars_update", methods={"GET", "POST"}) */
    public function __invoke(Request $request, Car $car): Response
    {
        $form = $this->createForm(ProfileCarType::class, $car);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $request->headers->has('refresh-form')) {
            $form->clearErrors(true);

            return $this->render('front/_common/_plain_form.html.twig', [
                'form' => $form->createView(),
            ]);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $this->carRepository->flush();
            $this->addUpdatedFlash();

            return $this->redirectToRoute('profile_cars_list');
        }

        return $this->render('front/profile/profile_form_view.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}