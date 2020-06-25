<?php

declare(strict_types=1);

namespace App\Controller\Admin\Car\Model;

use App\Controller\BaseController;
use App\Entity\Car\Model\Model;
use App\Form\Car\Model\ModelType;
use App\Repository\Car\Model\ModelRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/admin/models") */
class UpdateModelAction extends BaseController
{
    private ModelRepository $modelRepository;

    public function __construct(ModelRepository $modelRepository)
    {
        $this->modelRepository = $modelRepository;
    }

    /** @Route("/update/{id}", name="admin_models_update", methods={"GET", "POST"}) */
    public function __invoke(Request $request, Model $model): Response
    {
        $form = $this->createForm(ModelType::class, $model);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->modelRepository->flush();
            $this->addUpdatedFlash();

            return $this->redirectToRoute('admin_models_list');
        }

        return $this->render('admin/_common/form_view.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}