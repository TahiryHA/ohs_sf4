<?php

namespace App\Controller\Admin;

use App\Entity\Slider;
use App\Form\SliderType;
use App\Repository\SliderRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/slider")
 */
class SliderController extends AbstractController
{
    /**
     * @Route("/", name="slider_index", methods={"GET"})
     */
    public function index(SliderRepository $sliderRepository): Response
    {
        return $this->render('slider/index.html.twig', [
            'sliders' => $sliderRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="slider_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $slider = new Slider();
        $form = $this->createForm(SliderType::class, $slider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($slider);
            $entityManager->flush();

            return $this->redirectToRoute('slider_index');
        }

        return $this->render('slider/new.html.twig', [
            'slider' => $slider,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="slider_show", methods={"GET"})
     */
    public function show(Slider $slider): Response
    {
        return $this->render('slider/show.html.twig', [
            'slider' => $slider,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="slider_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Slider $slider, CacheManager $cacheManager, UploaderHelper $helper): Response
    {
        $form = $this->createForm(SliderType::class, $slider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            if ($slider->getImageFile() instanceof UploadedFile) {
                $cacheManager->remove($helper->asset($slider,'imageFile'));
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('slider_index');
        }

        return $this->render('slider/edit.html.twig', [
            'slider' => $slider,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="slider_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Slider $slider, CacheManager $cacheManager, UploaderHelper $helper): Response
    {
        if ($this->isCsrfTokenValid('delete'.$slider->getId(), $request->request->get('_token'))) {

            $entityManager = $this->getDoctrine()->getManager();
            
            $cacheManager->remove($helper->asset($slider,'imageFile'));
            $filesystem = new Filesystem();
            $filesystem->remove('images/'.$slider->getImage());

            $entityManager->remove($slider);

            $entityManager->flush();
        }

        return $this->redirectToRoute('slider_index');
    }
}
