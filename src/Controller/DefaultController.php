<?php

/*
 * This file is part of the COURAT application.
 *
 * (c) Bechir Ba and contributors
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Service\Cart\CartService;
use App\Repository\UserRepository;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="base_index")
     */
    public function index(ArticlesRepository $articlesRepository): Response
    {
        

        return $this->render('default/index.html.twig', [
            'publications' => $articlesRepository->findAll()
        ]);
    }

    

    public function left(CartService $cartService, $active  ){

        $panierWithData = $cartService->getFullCart();

        return $this->render("admin/common/left-sidebar.twig",[
            'items' => $panierWithData,
            'active' => $active
        ]);
    }


}
