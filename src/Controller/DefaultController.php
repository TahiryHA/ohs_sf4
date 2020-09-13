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

use App\Entity\News;
use App\Entity\User;
use App\Entity\Level;
use App\Entity\Slider;
use App\Entity\Gallery;
use App\Entity\Articles;
use App\Entity\Categories;
use App\Entity\Parallax;
use App\Entity\SocialNetwork;
use App\Service\Cart\CartService;
use App\Repository\UserRepository;
use App\Repository\ArticlesRepository;
use App\Repository\ParameterRepository;
use App\Repository\CategoriesRepository;
use App\Repository\SocialNetworkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
            'data' => $this->getData($em),
            'active' => 'home'
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

        $now = date_format(new \DateTime('now'), 'Y-m-d');

        $doc = $this->getDoctrine();

        $sliders = $doc->getRepository(Slider::class)->findAll();
        $annonces = $doc->getRepository(Articles::class)->findBy([], [], 3);
        $teach = $doc->getRepository(User::class)->findAll();
        $galleries = $doc->getRepository(Gallery::class)->findBy([], [], 4);
        $parallax = $doc->getRepository(Parallax::class)->findOneBy([],[],1);
        $sn = $doc->getRepository(SocialNetwork::class)->findAll();


        $news = [];

        if ($this->getuser()) {
            $role = [];
            foreach ($this->getUser()->getRoles() as $key => $value) {
                $role = $value;
            }

            if ($role == 'ROLE_STUDENT') {
                $levels = $this->getUser()->getLevel();

                $level = [];
                foreach ($levels as $key => $value) {
                    $level = $value->getId();
                }
                if ($level) {
                    $mylevel = $doc->getRepository(Level::class)->findOneBy(['id' => $level], [], 3);

                foreach ($mylevel->getNews() as $key => $level) {
                    $news[] = $level;
                }
                    # code...
                }
            }
        }


        $teachers = [];

        $roles = [
            'ROLE_TEACHER' => 'Enseignant'
        ];

        foreach ($teach as $key => $value) {

            // get the UploaderHelper service...

            foreach ($value->getRoles() as $role) {

                if ($role == 'ROLE_TEACHER') {
                    $teachers[] = [
                        'src' => $this->helper->asset($value),
                        'username' => $value->getUsername(),
                        'about' => $value->getAbout(),
                        'role' => $roles[$role],
                        'socialNetwork' => $value->getSocialNetwork()
                    ];
                }
            }
        }
        $dataAnnonces = [];
        foreach ($annonces as $key => $value) {
            $dataAnnonces [] = [
                'id' => $value->getId(),
                'image' => $this->helper->asset($value),
                'title' => $value->getTitle(),
                'content' => strip_tags($value->getContent()),
                'createdAt' => $value->getCreatedAt()
            ];
        }

        $dataNews = [];
        foreach ($news as $key => $value) {
            $dataNews[] = [
                'id' => $value->getId(),
                'image' => $this->helper->asset($value),
                'title' => $value->getTitle(),
                'content' => strip_tags($value->getContent()),
                'createdAt' => $value->getCreatedAt()
            ];
        }
        return [
            'sliders' => $sliders,
            'annonces' => $dataAnnonces,
            'news' => $dataNews,
            'teachers' => $teachers,
            'galleries' => $galleries,
            'parallax' => $parallax,
            'social_networks' => $sn

        ];
    }

    public function teachers_index(UserRepository $repo, SocialNetworkRepository $sn)
    {

        $teach = $repo->findAll();
        $teachers = [];

        $roles = [
            'ROLE_TEACHER' => 'Enseignant'
        ];

        foreach ($teach as $key => $value) {

            // get the UploaderHelper service...

            foreach ($value->getRoles() as $role) {

                if ($role == 'ROLE_TEACHER') {
                    $teachers[] = [
                        'src' => $this->helper->asset($value),
                        'username' => $value->getUsername(),
                        'about' => $value->getAbout(),
                        'role' => $roles[$role],
                        'socialNetwork' => $value->getSocialNetwork()
                    ];
                }
            }
        }

        return $this->render('teacher/index.html.twig', [
            'teachers' => $teachers,
            'active' => 'teacher',
            'social_networks' => $sn



        ]);
    }

    public function annonce_show(
        Articles $article,
        ArticlesRepository $repo,
        CategoriesRepository $caterepo
    ): Response {
        return $this->render('articles/show.html.twig', [
            'annonce' => $article,
            'annonces' => $repo->findAll(),
            'regions' => $caterepo->findAll(),
            'active' => 'home'

        ]);
    }

    public function new_show(
        News $news
        ): Response
    {
        return $this->render('news/show.html.twig', [
            'news' => $news,
            'level' => $news->getLevel(),
            'active' => 'home'
        ]);
    }

    public function region_show(PaginatorInterface $paginator, Categories $categories, Request $request): Response
    {
        $result = $paginator->paginate($categories->getArticles(), $request->query->getInt('page', 1), 6);

        return $this->render('categories/index.html.twig', [
            'regions' => $result,
            'name' => $categories->getName(),
            'active' => 'home'

        ]);
    }

    public function level_show(PaginatorInterface $paginator, Level $level, Request $request): Response
    {
        $result = $paginator->paginate($level->getNews(), $request->query->getInt('page', 1), 6);
        
        return $this->render('level/index.html.twig', [
            'level' => $result,
            'name' => $level->getName(),
            'active' => 'home'

        ]);
    }

    public function left(CartService $cartService, $active)
    {

        $panierWithData = $cartService->getFullCart();

        return $this->render("admin/common/left-sidebar.twig", [
            'items' => $panierWithData,
            'active' => $active
        ]);
    }

    public function about_index(ParameterRepository $repo)
    {

        return $this->render("default/about.html.twig", [
            'data' => $repo->getParameter(),
            'active' => 'about'
        ]);
    }

    public function footer(ParameterRepository $repo, ArticlesRepository $repoarticle, SocialNetworkRepository $sn)
    {

        return $this->render("common/footer.twig", [
            'data' => $repo->getParameter(),
            'annonces' => $repoarticle->findAll(),
            'social_networks' => $sn->findAll()

        ]);
    }
}
