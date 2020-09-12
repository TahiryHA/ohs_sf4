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

use App\Entity\User;
use App\Entity\Slider;
use App\Entity\Gallery;
use App\Entity\Articles;
use App\Entity\Categories;
use App\Service\Cart\CartService;
use App\Repository\ArticlesRepository;
use App\Repository\ParameterRepository;
use App\Repository\CategoriesRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{
    private $helper;

    public function __construct(UploaderHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     *@Route("/",name="admin_index") 
     */
    public function index(EntityManagerInterface $em)
    {
        return $this->render('default/index.html.twig', [
            'data' => $this->getData($em)
        ]);
    }

    public function info(ParameterRepository $repo)
    {
        return $this->render('common/info.twig', [
            'data' => $repo->getParameter()
        ]);
    }

    public function getData(EntityManagerInterface $em)
    {

        $now = date_format(new \DateTime('now'),'Y-m-d');

        $doc = $this->getDoctrine();

        $sliders = $doc->getRepository(Slider::class)->findAll();
        $annonces = $doc->getRepository(Articles::class)->findBy([],[],3);
        $teach = $doc->getRepository(User::class)->findAll();
        $galleries = $doc->getRepository(Gallery::class)->findBy([],[],4);

        $teachers = [];

        $roles = [
            'ROLE_TEACHER' => 'Enseignant'
        ];
        
        foreach ($teach as $key => $value) {
      
            // get the UploaderHelper service...
       
            foreach ($value->getRoles() as $role) {
       
                if ($role == 'ROLE_TEACHER') {
                    $teachers [] = [
                        'src' => $this->helper->asset($value),
                        'username' => $value->getUsername(),
                        'about' => $value->getAbout(),
                        'role' => $roles[$role]
                    ];
                }
            }
            
        }
        return [
            'sliders' => $sliders,
            'annonces' => $annonces,
            'teachers' => $teachers,
            'galleries' => $galleries
        ];
    }

    public function teachers_index(UserRepository $repo){

        $teach = $repo->findAll();
        $teachers = [];

        $roles = [
            'ROLE_TEACHER' => 'Enseignant'
        ];
        
        foreach ($teach as $key => $value) {
      
            // get the UploaderHelper service...
       
            foreach ($value->getRoles() as $role) {
       
                if ($role == 'ROLE_TEACHER') {
                    $teachers [] = [
                        'src' => $this->helper->asset($value),
                        'username' => $value->getUsername(),
                        'about' => $value->getAbout(),
                        'role' => $roles[$role]
                    ];
                }
            }
            
        }

        return $this->render('teacher/index.html.twig', [
            'teachers' => $teachers,


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

    public function annonce_show(
        Articles $article, 
        ArticlesRepository $repo,
        CategoriesRepository $caterepo
        ): Response
    {
        return $this->render('articles/show.html.twig', [
            'annonce' => $article,
            'annonces' => $repo->findAll(),
            'regions' => $caterepo->findAll()

        ]);
    }

    public function region_show(PaginatorInterface $paginator, Categories $categories, Request $request): Response
    {
        $result = $paginator->paginate($categories->getArticles(),$request->query->getInt('page', 1),6) ;

        return $this->render('categories/index.html.twig', [
            'regions' => $result,
            'name' => $categories->getName()

        ]);
    }

    public function left(CartService $cartService, $active  ){

        $panierWithData = $cartService->getFullCart();

        return $this->render("admin/common/left-sidebar.twig",[
            'items' => $panierWithData,
            'active' => $active
        ]);
    }

    public function about_index(ParameterRepository $repo){

        return $this->render("default/about.html.twig",[
            'data' => $repo->getParameter(),
        ]);
    }

    public function footer(ParameterRepository $repo, ArticlesRepository $repoarticle){

        return $this->render("common/footer.twig",[
            'data' => $repo->getParameter(),
            'annonces' => $repoarticle->findAll()
        ]);
    }

}
