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

use App\Entity\Articles;
use App\Service\Cart\CartService;
use App\Repository\ArticlesRepository;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{

    public function index(ArticlesRepository $articlesRepository): Response
    {
        return $this->render('default/index.html.twig', [
            'annonces' => $articlesRepository->findAll()
        ]);
    }

    /**
     * @Route("/contact", name="contact_index")
     */
    public function contact(ArticlesRepository $articlesRepository): Response
    {
        return $this->render('default/contact.html.twig', [
            'annonces' => $articlesRepository->findAll()
        ]);
    }

    public function annonce_show(Articles $article, ArticlesRepository $repo): Response
    {
        return $this->render('default/annonce-show.html.twig', [
            'annonce' => $article,
            'annonces' => $repo->findAll()
        ]);
    }

    

    public function left(CartService $cartService, $active  ){

        $panierWithData = $cartService->getFullCart();

        return $this->render("admin/common/left-sidebar.twig",[
            'items' => $panierWithData,
            'active' => $active
        ]);
    }

    public function footer(CategoriesRepository $repo){

       
        return $this->render("common/footer.html.twig",[
            'regions' => $repo->findAll()
        ]);
    }


}
