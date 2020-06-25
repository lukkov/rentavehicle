<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/admin") */
class DefaultController extends BaseController
{
    /** @Route("/", name="admin_default") */
    public function __invoke(): Response
    {
        return $this->redirectToRoute('admin_brands_list');
    }
}