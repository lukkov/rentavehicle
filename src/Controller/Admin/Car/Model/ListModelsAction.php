<?php

declare(strict_types=1);

namespace App\Controller\Admin\Car\Model;

use App\Controller\BaseController;
use App\Form\Car\Model\ModelFilterType;
use App\Repository\Car\Model\ModelRepository;
use App\Service\Pager\Pager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/admin/models") */
class ListModelsAction extends BaseController
{
    private ModelRepository $modelRepository;
    private Pager $pager;

    public function __construct(ModelRepository $modelRepository, Pager $pager)
    {
        $this->modelRepository = $modelRepository;
        $this->pager = $pager;
    }

    /** @Route("/", name="admin_models_list", methods={"GET"}) */
    public function __invoke(Request $request): Response
    {
        $qb = $this->modelRepository->findAllQueryBuilder();
        $modelsFilterForm = $this->createForm(ModelFilterType::class);
        $modelsFilterForm->handleRequest($request);
        if ($modelsFilterForm->isSubmitted() && $modelsFilterForm->isValid()) {
            $this->modelRepository->addWhere($modelsFilterForm->getData(), $qb);
        }
        $pager = $this->pager->getPager($qb, (int) $request->query->get('page', 1));

        return $this->render('admin/models/list.html.twig', [
            'modelsFilterForm' => $modelsFilterForm->createView(),
            'models' => $pager->getCurrentPageResults(),
            'pager' => $pager,
        ]);
    }
}