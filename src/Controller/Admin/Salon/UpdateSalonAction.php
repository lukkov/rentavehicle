<?php

declare(strict_types=1);

namespace App\Controller\Admin\Salon;

use App\Controller\BaseController;
use App\Entity\Salon\Salon;
use App\Form\Salon\SalonType;
use App\Repository\Salon\SalonRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/admin/salon") */
class UpdateSalonAction extends BaseController
{
    private SalonRepository $salonRepository;

    public function __construct(SalonRepository $salonRepository)
    {
        $this->salonRepository = $salonRepository;
    }

    /** @Route("/update/{id}", name="admin_salons_update", methods={"GET", "POST"}) */
    public function __invoke(Request $request, Salon $salon): Response
    {
        $form = $this->createForm(SalonType::class, $salon);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->salonRepository->flush();
            $this->addUpdatedFlash();

            return $this->redirectToRoute('admin_salons_list');
        }

        return $this->render('admin/salons/salons_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}