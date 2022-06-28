<?php

namespace App\Controller\BackOffice;

use App\Entity\Technology;
use App\Form\TechnologyType;
use App\Repository\TechnologyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back/technology")
 */
class TechnologyController extends AbstractController
{
    /**
     * @Route("", name="back_technology_index", methods={"GET"})
     */
    public function index(TechnologyRepository $technologyRepository): Response
    {
        return $this->render('technology/index.html.twig', [
            'technologies' => $technologyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="back_technology_new", methods={"GET", "POST"})
     */
    public function new(Request $request, TechnologyRepository $technologyRepository): Response
    {
        $technology = new Technology();
        $form = $this->createForm(TechnologyType::class, $technology);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $technologyRepository->add($technology, true);

            $this->addFlash('success', 'Technologie : '. $technology->getTechnologyName() .' ajoutée.');
            return $this->redirectToRoute('back_technology_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('technology/new.html.twig', [
            'technology' => $technology,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="back_technology_show", methods={"GET"})
     */
    public function show(Technology $technology): Response
    {
        return $this->render('technology/show.html.twig', [
            'technology' => $technology,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="back_technology_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Technology $technology, TechnologyRepository $technologyRepository): Response
    {
        $form = $this->createForm(TechnologyType::class, $technology);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $technologyRepository->add($technology, true);

            $this->addFlash('warning', 'Technologie : '. $technology->getTechnologyName() .' modifiée.');
            return $this->redirectToRoute('back_technology_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('technology/edit.html.twig', [
            'technology' => $technology,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="back_technology_delete", methods={"POST"})
     */
    public function delete(Request $request, Technology $technology, TechnologyRepository $technologyRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$technology->getId(), $request->request->get('_token'))) {
            
            $this->addFlash('danger', 'Technologie : '. $technology->getTechnologyName() .' supprimée.');
            $technologyRepository->remove($technology, true);
        }

        return $this->redirectToRoute('back_technology_index', [], Response::HTTP_SEE_OTHER);
    }
}
