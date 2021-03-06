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

use App\Entity\Level;
use App\Entity\User;
use App\Form\Admin\UserType;
use App\Form\PasswordEditType;
use App\Repository\SocialNetworkRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Controller that manage security part of the backend.
 *
 * @Route("/user")
 */
class UserController extends AbstractController
{
    private $breadcrumbs;
    public function __construct(Breadcrumbs $breadcrumbs)
    {
        $this->breadcrumbs = $breadcrumbs;
    }
    
    /**
     * @Route("/", name="admin_users")
     */
    public function index(): Response
    {
        $this->breadcrumbs->prependRouteItem("Acceuil", "admin_index");
        $this->breadcrumbs->addItem("Utilisateurs");
        $this->breadcrumbs->addItem("Liste");

        return $this->render('admin/user/list.html.twig', [
          'users' => $this->getDoctrine()->getRepository(User::class)->findAll(),
        ]);
    }

    /**
     * Retrieve the lateste registrated users from the database.
     */
    public function latest(UserRepository $repo): Response
    {
        $users = $repo->getLatest();

        return $this->render('admin/user/table.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_user_edit")
     */
    public function edit(User $user, Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder,SocialNetworkRepository $sn): Response
    {
        $this->breadcrumbs->prependRouteItem("Acceuil", "admin_index");
        $this->breadcrumbs->addItem("Utilisateurs");
        $this->breadcrumbs->addItem("Modifier");


        $form = $this->createForm(UserType::class, $user);

        $form->remove('plainPassword');
        $form->remove('level');
        $form->remove('academic_year');
        $form->remove('roles');




        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            

            $sn = $request->request->get('sn');

            $data = [];
            foreach ($sn as $key => $value) {
                $data[$key] = $value;
            }
 
            $user->setSocialNetwork($data);

            $em->flush();

            $this->addFlash('success', "L'utilisateur a été modifiée avec succès.");

            return $this->redirectToRoute('admin_users');
        }

        return $this->render('admin/user/create.html.twig', [
            'form' => $form->createView(),
            'social_networks' => $sn->findAll(),

        ]);
    }

    /**
     * @Route("/new", name="admin_user_create")
     */
    public function create(?User $user, Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder,SocialNetworkRepository $sn): Response
    {
        $this->breadcrumbs->prependRouteItem("Acceuil", "admin_index");
        $this->breadcrumbs->addItem("Utilisateurs");
        $this->breadcrumbs->addItem("Ajouter");

        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $roles = [];

            foreach ($form->get('roles')->getData() as $role) {
                $roles[] = $role->getType();
            }

            $data = $request->request->get('user');
            $level = $this->getDoctrine()->getRepository(Level::class)->find($data['level']);

            $user->setRoles($roles);
            $user->addLevel($level);

            $sn = $request->request->get('sn');

            $data = [];
            foreach ($sn as $key => $value) {
                $data[$key] = $value;
            }
 
            $user->setSocialNetwork($data);

            $em->persist($user);

            $em->flush();

            $this->addFlash('success', "L'utilisateur a été créé avec succès.");

            return $this->redirectToRoute('admin_users');
        }

        return $this->render('admin/user/create.html.twig', [
            'form' => $form->createView(),
            'social_networks' => $sn->findAll(),

        ]);
    }

    /**
     * @Route("/change-password", name="admin_user_password_edit")
     */
    public function editPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();
        $form = $this->createForm(PasswordEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Le mot de passe a été changé.');

            return $this->redirectToRoute('admin_index');
        }

        return $this->render('admin/user/password-edit.html.twig', [
            'active' => 'editPassword',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{user}", name="admin_user_show")
     */
    public function show(User $user): Response
    {
        $this->breadcrumbs->prependRouteItem("Acceuil", "admin_index");
        $this->breadcrumbs->addItem("Utilisateurs");
        $this->breadcrumbs->addItem($user->getUsername());

        return $this->render('admin/user/show.html.twig', [
          'user' => $user,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_user_delete", methods={"POST"})
     */
    public function deleteUser(User $user, EntityManagerInterface $em)
    {
        if (!$user) {
            $this->addFlash('danger', "L'utilisateur est introuvable.");
        } else {
            $em->remove($user);
            $em->flush();
            $this->addFlash('success', "L'utilisateur a été supprimé.");
        }

        return $this->redirectToRoute('admin_users');
    }
}
