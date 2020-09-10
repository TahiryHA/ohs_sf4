<?php

/*
 * This file is part of the COURAT application.
 *
 * (c) Bechir Ba and contributors
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Admin\Setting;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/settings")
 */
class Controller extends AbstractController
{
    private $breadcrumbs;
    public function __construct(Breadcrumbs $breadcrumbs)
    {
        $this->breadcrumbs = $breadcrumbs;
    }
    
    /**
     * @Route("/", name="admin_settings")
     */
    public function settings(): Response
    {
        $this->breadcrumbs->prependRouteItem("Acceuil", "admin_index");
        $this->breadcrumbs->addItem("Paramètres");

        return $this->render('admin/settings/index.html.twig', [
            'active' => null,
        ]);
    }

    /**
     * @Route("/users", name="admin_users_setting")
     */
    public function users(EntityManagerInterface $em): Response
    {
        $this->breadcrumbs->prependRouteItem("Acceuil", "admin_index");
        $this->breadcrumbs->addItem("Paramètres");

        $users = $em->getRepository(User::class)->findAll();

        return $this->render('admin/settings/user/index.html.twig', [
            'active' => 'setting-users',
            'users' => $users,
        ]);
    }
}
