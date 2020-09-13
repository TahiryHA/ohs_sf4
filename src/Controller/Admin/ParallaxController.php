<?php

namespace App\Controller\Admin;

use App\Entity\Parallax;
use App\Form\ParallaxType;
use App\Repository\ParallaxRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/parallax")
 */
class ParallaxController extends AbstractController
{
    /**
     * @Route("/", name="parallax_index", methods={"GET"})
     */
    public function index(ParallaxRepository $parallaxRepository): Response
    {
        return $this->render('parallax/index.html.twig', [
            'parallaxes' => $parallaxRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="parallax_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $parallax = new Parallax();
        $form = $this->createForm(ParallaxType::class, $parallax);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($parallax);
            $entityManager->flush();

            return $this->redirectToRoute('parallax_index');
        }

        return $this->render('parallax/new.html.twig', [
            'parallax' => $parallax,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="parallax_show", methods={"GET"})
     */
    public function show(Parallax $parallax): Response
    {
        return $this->render('parallax/show.html.twig', [
            'parallax' => $parallax,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="parallax_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Parallax $parallax): Response
    {
        $form = $this->createForm(ParallaxType::class, $parallax);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('parallax_index');
        }

        return $this->render('parallax/edit.html.twig', [
            'parallax' => $parallax,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="parallax_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Parallax $parallax): Response
    {
        if ($this->isCsrfTokenValid('delete'.$parallax->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($parallax);
            $entityManager->flush();
        }

        return $this->redirectToRoute('parallax_index');
    }
}
