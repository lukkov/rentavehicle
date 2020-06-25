<?php

declare(strict_types=1);

namespace App\Controller\Admin\City;

use App\Controller\BaseController;
use App\Form\City\CityType;
use App\Repository\City\CityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/admin/city") */
class CreateCityAction extends BaseController
{
    private CityRepository $cityRepository;

    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    /** @Route("/create", name="admin_cities_create", methods={"GET", "POST"}) */
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(CityType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->cityRepository->save($form->getData());
            $this->addCreatedFlash();

            return $this->redirectToRoute('admin_cities_list');
        }

        return $this->render('admin/_common/form_view.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}