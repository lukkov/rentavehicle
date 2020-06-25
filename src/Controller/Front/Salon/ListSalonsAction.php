<?php

declare(strict_types=1);

namespace App\Controller\Front\Salon;

use App\Controller\BaseController;
use App\Form\Salon\SalonFilterType;
use App\Repository\Salon\SalonRepository;
use App\Service\Pager\Pager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListSalonsAction extends BaseController
{
    private const ELEMENTS_PER_PAGE = 6;

    private SalonRepository $salonRepository;
    private Pager $pager;

    public function __construct(SalonRepository $salonRepository, Pager $pager)
    {
        $this->salonRepository = $salonRepository;
        $this->pager = $pager;
    }

    /** @Route("/salons", name="salon_list", methods={"GET"}) */
    public function __invoke(Request $request): Response
    {
        $qb = $this->salonRepository->findAllQueryBuilder();
        $salonsFilterForm = $this->createForm(SalonFilterType::class);
        $salonsFilterForm->handleRequest($request);
        if ($salonsFilterForm->isSubmitted() && $salonsFilterForm->isValid()) {
            $this->salonRepository->addWhere($salonsFilterForm->getData(), $qb);
        }
        $pager = $this->pager->getPager($qb, (int) $request->query->get('page', 1), self::ELEMENTS_PER_PAGE);

        return $this->render('front/salons/list.html.twig', [
            'salonsFilterForm' => $salonsFilterForm->createView(),
            'salons' => $pager->getCurrentPageResults(),
            'pager' => $pager,
        ]);
    }
}