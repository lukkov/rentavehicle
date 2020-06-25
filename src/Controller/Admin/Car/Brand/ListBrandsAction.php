<?php

declare(strict_types=1);

namespace App\Controller\Admin\Car\Brand;

use App\Controller\BaseController;
use App\Form\Car\Brand\BrandFilterType;
use App\Repository\Car\Brand\BrandRepository;
use App\Service\Pager\Pager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/admin/brands") */
class ListBrandsAction extends BaseController
{
    private BrandRepository $brandRepository;
    private Pager $pager;

    public function __construct(BrandRepository $brandRepository, Pager $pager)
    {
        $this->brandRepository = $brandRepository;
        $this->pager = $pager;
    }

    /** @Route("/", name="admin_brands_list", methods={"GET"}) */
    public function __invoke(Request $request): Response
    {
        $qb = $this->brandRepository->findAllQueryBuilder();
        $brandsFilterForm = $this->createForm(BrandFilterType::class);
        $brandsFilterForm->handleRequest($request);
        if ($brandsFilterForm->isSubmitted() && $brandsFilterForm->isValid()) {
            $this->brandRepository->addWhere($brandsFilterForm->getData(), $qb);
        }
        $pager = $this->pager->getPager($qb, (int) $request->query->get('page', 1));

        return $this->render('admin/brands/list.html.twig', [
            'brandsFilterForm' => $brandsFilterForm->createView(),
            'brands' => $pager->getCurrentPageResults(),
            'pager' => $pager,
        ]);
    }
}