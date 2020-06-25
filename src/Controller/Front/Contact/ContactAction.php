<?php

declare(strict_types=1);

namespace App\Controller\Front\Contact;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactAction extends BaseController
{
    /** @Route("/contact", name="contact", methods={"GET"}) */
    public function __invoke(): Response
    {
        return $this->render('front/contact/contact.html.twig');
    }
}