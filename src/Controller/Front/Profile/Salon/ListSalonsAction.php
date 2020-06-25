<?php

declare(strict_types=1);

namespace App\Controller\Front\Profile\Salon;

use App\Controller\BaseController;
use App\Form\Salon\SalonFilterType;
use App\Repository\Salon\SalonRepository;
use App\Service\Pager\Pager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/profile/salon") */
class ListSalonsAction extends BaseController
{
    private SalonRepository $salonRepository;
    private Pager $pager;

    public function __construct(SalonRepository $salonRepository, Pager $pager)
    {
        $this->salonRepository = $salonRepository;
        $this->pager = $pager;
    }

    /** @Route("/", name="profile_salons_list", methods={"GET"}) */
    public function __invoke(Request $request): Response
    {
        $qb = $this->salonRepository->findByOwnerQueryBuilder($this->getUser());
        $salonFilterForm = $this->createForm(SalonFilterType::class);
        $salonFilterForm->handleRequest($request);
        if ($salonFilterForm->isSubmitted() && $salonFilterForm->isValid()) {
            $this->salonRepository->addWhere($salonFilterForm->getData(), $qb);
        }
        $pager = $this->pager->getPager($qb, (int) $request->query->get('page', 1));

        return $this->render('front/profile/salon/list.html.twig', [
            'salonsFilterForm' => $salonFilterForm->createView(),
            'salons' => $pager->getCurrentPageResults(),
            'pager' => $pager,
        ]);
    }
}