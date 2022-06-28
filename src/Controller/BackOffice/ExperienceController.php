<?php

namespace App\Controller\BackOffice;

use App\Entity\Experience;
use App\Form\ExperienceType;
use App\Repository\ExperienceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back/experience")
 */
class ExperienceController extends AbstractController
{
    /**
     * @Route("/", name="back_experience_index", methods={"GET"})
     */
    public function index(ExperienceRepository $experienceRepository): Response
    {
        return $this->render('experience/index.html.twig', [
            'experiences' => $experienceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="back_experience_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ExperienceRepository $experienceRepository): Response
    {
        $experience = new Experience();
        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $experienceRepository->add($experience, true);

            $this->addFlash('success', 'Expérience : '. $experience->getYearsNumber() .' ajoutée.');
            return $this->redirectToRoute('back_experience_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('experience/new.html.twig', [
            'experience' => $experience,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="back_experience_show", methods={"GET"})
     */
    public function show(Experience $experience): Response
    {
        return $this->render('experience/show.html.twig', [
            'experience' => $experience,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="back_experience_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Experience $experience, ExperienceRepository $experienceRepository): Response
    {
        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $experienceRepository->add($experience, true);

            $this->addFlash('warning', 'Expérience : '. $experience->getYearsNumber() .' modifiée.');
            return $this->redirectToRoute('back_experience_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('experience/edit.html.twig', [
            'experience' => $experience,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="back_experience_delete", methods={"POST"})
     */
    public function delete(Request $request, Experience $experience, ExperienceRepository $experienceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$experience->getId(), $request->request->get('_token'))) {
            
            $this->addFlash('danger', 'Expérience : '. $experience->getYearsNumber() .' supprimée.');
            $experienceRepository->remove($experience, true);
        }

        return $this->redirectToRoute('back_experience_index', [], Response::HTTP_SEE_OTHER);
    }
}
