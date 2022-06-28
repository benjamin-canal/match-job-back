<?php

namespace App\Controller\BackOffice;

use App\Entity\Jobtitle;
use App\Form\JobtitleType;
use App\Repository\JobtitleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back/jobtitle")
 */
class JobtitleController extends AbstractController
{
    /**
     * @Route("", name="back_jobtitle_index", methods={"GET"})
     */
    public function index(JobtitleRepository $jobtitleRepository): Response
    {
        return $this->render('jobtitle/index.html.twig', [
            'jobtitles' => $jobtitleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="back_jobtitle_new", methods={"GET", "POST"})
     */
    public function new(Request $request, JobtitleRepository $jobtitleRepository): Response
    {
        $jobtitle = new Jobtitle();
        $form = $this->createForm(JobtitleType::class, $jobtitle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $jobtitleRepository->add($jobtitle, true);

            $this->addFlash('success', 'Titre d\'emplois : '. "$jobtitle->getTitle()" .' ajouté.');
            return $this->redirectToRoute('back_jobtitle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('jobtitle/new.html.twig', [
            'jobtitle' => $jobtitle,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="back_jobtitle_show", methods={"GET"})
     */
    public function show(Jobtitle $jobtitle): Response
    {
        return $this->render('jobtitle/show.html.twig', [
            'jobtitle' => $jobtitle,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="back_jobtitle_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Jobtitle $jobtitle, JobtitleRepository $jobtitleRepository): Response
    {
        $form = $this->createForm(JobtitleType::class, $jobtitle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $jobtitleRepository->add($jobtitle, true);

            $this->addFlash('warning', 'Titre d\'emplois '. $jobtitle->getTitle() .' modifié.');
            return $this->redirectToRoute('back_jobtitle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('jobtitle/edit.html.twig', [
            'jobtitle' => $jobtitle,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="back_jobtitle_delete", methods={"POST"})
     */
    public function delete(Request $request, Jobtitle $jobtitle, JobtitleRepository $jobtitleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$jobtitle->getId(), $request->request->get('_token'))) {
            
            $this->addFlash('danger', 'Titre d\'emplois '. $jobtitle->getTitle() .' supprimé.');
            $jobtitleRepository->remove($jobtitle, true);
        }

        return $this->redirectToRoute('back_jobtitle_index', [], Response::HTTP_SEE_OTHER);
    }
}
