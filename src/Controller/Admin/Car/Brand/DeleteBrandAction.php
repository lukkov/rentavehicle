<?php

declare(strict_types=1);

namespace App\Controller\Admin\Car\Brand;

use App\Controller\BaseController;
use App\Entity\Car\Brand\Brand;
use App\Repository\Car\Brand\BrandRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/admin/brands") */
class DeleteBrandAction extends BaseController
{
    private BrandRepository $brandRepository;

    public function __construct(BrandRepository $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }

    /** @Route("/delete/{id}", name="admin_brands_delete", methods={"GET"}) */
    public function __invoke(Brand $brand): Response
    {
        $this->brandRepository->delete($brand);
        $this->addDeletedFlash();

        return $this->redirectToRoute('admin_brands_list');
    }
}