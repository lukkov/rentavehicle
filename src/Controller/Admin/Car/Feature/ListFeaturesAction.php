<?php

declare(strict_types=1);

namespace App\Controller\Admin\Car\Feature;

use App\Controller\BaseController;
use App\Form\Car\Feature\FeatureFilterType;
use App\Repository\Car\Feature\FeatureRepository;
use App\Service\Pager\Pager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/admin/features") */
class ListFeaturesAction extends BaseController
{
    private FeatureRepository $featureRepository;
    private Pager $pager;

    public function __construct(FeatureRepository $featureRepository, Pager $pager)
    {
        $this->featureRepository = $featureRepository;
        $this->pager = $pager;
    }

    /** @Route("/", name="admin_features_list", methods={"GET"}) */
    public function __invoke(Request $request): Response
    {
        $qb = $this->featureRepository->findAllQueryBuilder();
        $featuresFilterForm = $this->createForm(FeatureFilterType::class);
        $featuresFilterForm->handleRequest($request);
        if ($featuresFilterForm->isSubmitted() && $featuresFilterForm->isValid()) {
            $this->featureRepository->addWhere($featuresFilterForm->getData(), $qb);
        }
        $pager = $this->pager->getPager($qb, (int) $request->query->get('page', 1));

        return $this->render('admin/features/list.html.twig', [
            'featuresFilterForm' => $featuresFilterForm->createView(),
            'features' => $pager->getCurrentPageResults(),
            'pager' => $pager,
        ]);
    }
}