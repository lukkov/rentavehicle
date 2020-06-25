<?php

declare(strict_types=1);

namespace App\Controller\Admin\Car;

use App\Controller\BaseController;
use App\Form\Car\CarFilterType;
use App\Repository\Car\CarRepository;
use App\Service\Pager\Pager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/admin/cars") */
class ListCarsAction extends BaseController
{
    private CarRepository $carRepository;
    private Pager $pager;

    public function __construct(CarRepository $carRepository, Pager $pager)
    {
        $this->carRepository = $carRepository;
        $this->pager = $pager;
    }

    /** @Route("/", name="admin_cars_list", methods={"GET"}) */
    public function __invoke(Request $request): Response
    {
        $qb = $this->carRepository->findAllQueryBuilder();
        $carsFilterForm = $this->createForm(CarFilterType::class);
        $carsFilterForm->handleRequest($request);
        if ($carsFilterForm->isSubmitted() && $carsFilterForm->isValid()) {
            $this->carRepository->addWhere($carsFilterForm->getData(), $qb);
        }
        $pager = $this->pager->getPager($qb, (int) $request->query->get('page', 1));

        return $this->render('admin/cars/list.html.twig', [
            'carsFilterForm' => $carsFilterForm->createView(),
            'cars' => $pager->getCurrentPageResults(),
            'pager' => $pager,
        ]);
    }
}