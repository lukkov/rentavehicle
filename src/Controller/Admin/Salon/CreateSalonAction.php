<?php

declare(strict_types=1);

namespace App\Controller\Admin\Salon;

use App\Controller\BaseController;
use App\Form\Salon\SalonType;
use App\Repository\Salon\SalonRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/admin/salon") */
class CreateSalonAction extends BaseController
{
    private SalonRepository $salonRepository;

    public function __construct(SalonRepository $salonRepository)
    {
        $this->salonRepository = $salonRepository;
    }

    /** @Route("/create", name="admin_salons_create", methods={"GET", "POST"}) */
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(SalonType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->salonRepository->save($form->getData());
            $this->addCreatedFlash();

            return $this->redirectToRoute('admin_salons_list');
        }

        return $this->render('admin/_common/form_view.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}