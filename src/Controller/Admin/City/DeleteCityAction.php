<?php

declare(strict_types=1);

namespace App\Controller\Admin\City;

use App\Controller\BaseController;
use App\Entity\City\City;
use App\Repository\City\CityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/admin/city") */
class DeleteCityAction extends BaseController
{
    private CityRepository $cityRepository;

    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    /** @Route("/delete/{id}", name="admin_cities_delete", methods={"GET"}) */
    public function __invoke(City $city): Response
    {
        $this->cityRepository->delete($city);
        $this->addDeletedFlash();

        return $this->redirectToRoute('admin_cities_list');
    }
}