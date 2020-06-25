<?php

declare(strict_types=1);

namespace App\Controller\Admin\Car\Feature;

use App\Controller\BaseController;
use App\Entity\Car\Feature\Feature;
use App\Form\Car\Feature\FeatureType;
use App\Repository\Car\Feature\FeatureRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/admin/features") */
class UpdateFeatureAction extends BaseController
{
    private FeatureRepository $featureRepository;

    public function __construct(FeatureRepository $featureRepository)
    {
        $this->featureRepository = $featureRepository;
    }

    /** @Route("/update/{id}", name="admin_features_update", methods={"GET", "POST"}) */
    public function __invoke(Request $request, Feature $feature): Response
    {
        $form = $this->createForm(FeatureType::class, $feature);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->featureRepository->flush();
            $this->addUpdatedFlash();

            return $this->redirectToRoute('admin_features_list');
        }

        return $this->render('admin/_common/form_view.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}