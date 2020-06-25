<?php

declare(strict_types=1);

namespace App\Controller\Admin\Car\Model;

use App\Controller\BaseController;
use App\Form\Car\Model\ModelType;
use App\Repository\Car\Model\ModelRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/admin/models") */
class CreateModelAction extends BaseController
{
    private ModelRepository $modelRepository;

    public function __construct(ModelRepository $modelRepository)
    {
        $this->modelRepository = $modelRepository;
    }

    /** @Route("/create", name="admin_models_create", methods={"GET", "POST"}) */
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(ModelType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->modelRepository->save($form->getData());
            $this->addCreatedFlash();

            return $this->redirectToRoute('admin_models_list');
        }

        return $this->render('admin/_common/form_view.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}