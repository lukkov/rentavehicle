<?php

declare(strict_types=1);

namespace App\Controller\Admin\City;

use App\Controller\BaseController;
use App\Form\City\CityFilterType;
use App\Repository\City\CityRepository;
use App\Service\Pager\Pager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/admin/city") */
class ListCitiesAction extends BaseController
{
    private CityRepository $cityRepository;
    private Pager $pager;

    public function __construct(CityRepository $cityRepository, Pager $pager)
    {
        $this->cityRepository = $cityRepository;
        $this->pager = $pager;
    }

    /** @Route("/", name="admin_cities_list", methods={"GET"}) */
    public function __invoke(Request $request): Response
    {
        $qb = $this->cityRepository->findAllQueryBuilder();
        $citiesFilterForm = $this->createForm(CityFilterType::class);
        $citiesFilterForm->handleRequest($request);
        if ($citiesFilterForm->isSubmitted() && $citiesFilterForm->isValid()) {
            $this->cityRepository->addWhere($citiesFilterForm->getData(), $qb);
        }
        $pager = $this->pager->getPager($qb, (int) $request->query->get('page', 1));

        return $this->render('admin/cities/list.html.twig', [
            'citiesFilterForm' => $citiesFilterForm->createView(),
            'cities' => $pager->getCurrentPageResults(),
            'pager' => $pager,
        ]);
    }
}