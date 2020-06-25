<?php

declare(strict_types=1);

namespace App\Controller\Admin\Car\Brand;

use App\Controller\BaseController;
use App\Entity\Car\Brand\Brand;
use App\Form\Car\Brand\BrandType;
use App\Repository\Car\Brand\BrandRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/admin/brands") */
class UpdateBrandAction extends BaseController
{
    private BrandRepository $brandRepository;

    public function __construct(BrandRepository $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }

    /** @Route("/update/{id}", name="admin_brands_update", methods={"GET", "POST"}) */
    public function __invoke(Request $request, Brand $brand): Response
    {
        $form = $this->createForm(BrandType::class, $brand);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->brandRepository->flush();
            $this->addUpdatedFlash();

            return $this->redirectToRoute('admin_brands_list');
        }

        return $this->render('admin/_common/form_view.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}