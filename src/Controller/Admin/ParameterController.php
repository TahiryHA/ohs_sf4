<?php

namespace App\Controller\Admin;

use App\Entity\Parameter;
use App\Form\ParameterType;
use App\Repository\ParameterRepository;
use App\Repository\SocialNetworkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/parameter")
 */
class ParameterController extends AbstractController
{
    /**
     * @Route("/", name="parameter_index", methods={"GET"})
     */
    public function index(ParameterRepository $parameterRepository): Response
    {
        return $this->render('parameter/index.html.twig', [
            'parameters' => $parameterRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="parameter_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $parameter = new Parameter();
        $form = $this->createForm(ParameterType::class, $parameter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $sn = $request->request->get('sn');

            $data = [];
            foreach ($sn as $key => $value) {
                $data[$key] = $value;
            }
 
            $parameter->setSocialNetwork($data);
            $entityManager->persist($parameter);
            $entityManager->flush();

            return $this->redirectToRoute('parameter_index');
        }

        return $this->render('parameter/new.html.twig', [
            'parameter' => $parameter,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="parameter_show", methods={"GET"})
     */
    public function show(Parameter $parameter): Response
    {
        return $this->render('parameter/show.html.twig', [
            'parameter' => $parameter,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="parameter_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Parameter $parameter, SocialNetworkRepository $sn): Response
    {
        $form = $this->createForm(ParameterType::class, $parameter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sn = $request->request->get('sn');

            $data = [];
            foreach ($sn as $key => $value) {
                $data[$key] = $value;
            }
 
            $parameter->setSocialNetwork($data);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('parameter_index');
        }

        return $this->render('parameter/edit.html.twig', [
            'parameter' => $parameter,
            'social_networks' => $sn->findAll(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="parameter_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Parameter $parameter): Response
    {
        if ($this->isCsrfTokenValid('delete'.$parameter->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($parameter);
            $entityManager->flush();
        }

        return $this->redirectToRoute('parameter_index');
    }
}
