<?php

declare(strict_types=1);

namespace App\Controller\Admin\User;

use App\Controller\BaseController;
use App\Form\User\UserFilterType;
use App\Repository\User\UserRepository;
use App\Service\Pager\Pager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/admin/users") */
class ListUsersAction extends BaseController
{
    private UserRepository $userRepository;
    private Pager $pager;

    public function __construct(UserRepository $userRepository, Pager $pager)
    {
        $this->userRepository = $userRepository;
        $this->pager = $pager;
    }

    /** @Route("/", name="admin_users_list", methods={"GET"}) */
    public function __invoke(Request $request): Response
    {
        $qb = $this->userRepository->findAllQueryBuilder();
        $usersFilterForm = $this->createForm(UserFilterType::class);
        $usersFilterForm->handleRequest($request);
        if ($usersFilterForm->isSubmitted() && $usersFilterForm->isValid()) {
            $this->userRepository->addWhere($usersFilterForm->getData(), $qb);
        }
        $pager = $this->pager->getPager($qb, (int) $request->query->get('page', 1));

        return $this->render('admin/users/list.html.twig', [
            'usersFilterForm' => $usersFilterForm->createView(),
            'users' => $pager->getCurrentPageResults(),
            'pager' => $pager,
        ]);
    }
}