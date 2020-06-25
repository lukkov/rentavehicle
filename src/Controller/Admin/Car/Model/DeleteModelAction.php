<?php

declare(strict_types=1);

namespace App\Controller\Admin\Car\Model;

use App\Controller\BaseController;
use App\Entity\Car\Model\Model;
use App\Repository\Car\Model\ModelRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/admin/models") */
class DeleteModelAction extends BaseController
{
    private ModelRepository $modelRepository;

    public function __construct(ModelRepository $modelRepository)
    {
        $this->modelRepository = $modelRepository;
    }

    /** @Route("/delete/{id}", name="admin_models_delete", methods={"GET", "POST"}) */
    public function __invoke(Model $model): Response
    {
        $this->modelRepository->delete($model);
        $this->addDeletedFlash();

        return $this->redirectToRoute('admin_models_list');
    }
}