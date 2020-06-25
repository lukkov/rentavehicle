<?php

declare(strict_types=1);

namespace App\Controller\Front\Profile\Salon;

use App\Controller\BaseController;
use App\Entity\Salon\Salon;
use App\Repository\Salon\SalonRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/profile/salon") */
class DeleteSalonAction extends BaseController
{
    private SalonRepository $salonRepository;

    public function __construct(SalonRepository $salonRepository)
    {
        $this->salonRepository = $salonRepository;
    }

    /** @Route("/delete/{id}", name="profile_salons_delete", methods={"GET"}) */
    public function __invoke(Salon $salon): Response
    {
        $this->salonRepository->delete($salon);
        $this->addDeletedFlash();

        return $this->redirectToRoute('profile_salons_list');
    }
}