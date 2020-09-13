<?php

/*
 * This file is part of the COURAT application.
 *
 * (c) Bechir Ba and contributors
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class HomeController extends AbstractController
{
    /**
     *@Route("/",name="admin_index") 
     */
    public function index(EntityManagerInterface $em)
    {
        return $this->render('admin/home/index.html.twig', [
            'data' => $this->getStats($em)
        ]);
    }

    public function getStats(EntityManagerInterface $em)
    {

        $now = date_format(new \DateTime('now'),'Y-m-d');

        $articles = $em->createQueryBuilder('a')->select('count(a.id)')
            ->from('App:Articles', 'a')
            ->getQuery()->getSingleScalarResult();
        
        $categories = $em->createQueryBuilder('c')->select('count(c.id)')
            ->from('App:Categories', 'c')
            ->getQuery()->getSingleScalarResult();
        
        $levels = $em->createQueryBuilder('c')->select('count(c.id)')
            ->from('App:Level', 'c')
            ->getQuery()->getSingleScalarResult();
    

        $users = $em->createQueryBuilder('c')->select('count(c.id)')
            ->from('App:User', 'c')
            ->where('c.roles LIKE :t OR c.roles LIKE :s OR c.roles LIKE :u')
            ->setParameter('t','%ROLE_TEACHER%')
            ->setParameter('s','%ROLE_STUDENT%')
            ->setParameter('u','%ROLE_USER%')


            ->getQuery()->getSingleScalarResult();

        return [
            'articles' => $articles,
            'categories' => $categories,
            'users' => $users,
            'levels' => $levels

        ];
    }
}
