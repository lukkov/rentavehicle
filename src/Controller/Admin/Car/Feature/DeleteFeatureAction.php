<?php

declare(strict_types=1);

namespace App\Controller\Admin\Car\Feature;

use App\Controller\BaseController;
use App\Entity\Car\Feature\Feature;
use App\Repository\Car\Feature\FeatureRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/admin/features") */
class DeleteFeatureAction extends BaseController
{
    private FeatureRepository $featureRepository;

    public function __construct(FeatureRepository $featureRepository)
    {
        $this->featureRepository = $featureRepository;
    }

    /** @Route("/delete/{id}", name="admin_features_delete", methods={"GET"}) */
    public function __invoke(Feature $feature): Response
    {
        $this->featureRepository->delete($feature);
        $this->addDeletedFlash();

        return $this->redirectToRoute('admin_features_list');
    }
}