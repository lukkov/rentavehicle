<?php

declare(strict_types=1);

namespace App\Controller\Admin\Car\Feature;

use App\Controller\BaseController;
use App\Form\Car\Feature\FeatureType;
use App\Repository\Car\Feature\FeatureRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/admin/features") */
class CreateFeatureAction extends BaseController
{
    private FeatureRepository $featureRepository;

    public function __construct(FeatureRepository $featureRepository)
    {
        $this->featureRepository = $featureRepository;
    }

    /** @Route("/create", name="admin_features_create", methods={"GET", "POST"}) */
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(FeatureType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->featureRepository->save($form->getData());
            $this->addCreatedFlash();

            return $this->redirectToRoute('admin_features_list');
        }

        return $this->render('admin/_common/form_view.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}