<?php

namespace App\Controller\BackOffice;

use App\Entity\Salary;
use App\Form\SalaryType;
use App\Repository\SalaryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back/salary")
 */
class SalaryController extends AbstractController
{
    /**
     * @Route("", name="back_salary_index", methods={"GET"})
     */
    public function index(SalaryRepository $salaryRepository): Response
    {
        return $this->render('salary/index.html.twig', [
            'salaries' => $salaryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="back_salary_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SalaryRepository $salaryRepository): Response
    {
        $salary = new Salary();
        $form = $this->createForm(SalaryType::class, $salary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $salaryRepository->add($salary, true);

            $this->addFlash('success', 'Tranche de salaire : '. $salary->getName() .' ajoutée.');
            return $this->redirectToRoute('back_salary_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('salary/new.html.twig', [
            'salary' => $salary,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="back_salary_show", methods={"GET"})
     */
    public function show(Salary $salary): Response
    {
        return $this->render('salary/show.html.twig', [
            'salary' => $salary,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="back_salary_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Salary $salary, SalaryRepository $salaryRepository): Response
    {
        $form = $this->createForm(SalaryType::class, $salary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $salaryRepository->add($salary, true);

            $this->addFlash('warning', 'Tranche de salaire : '. $salary->getName() .' modifiée.');
            return $this->redirectToRoute('back_salary_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('salary/edit.html.twig', [
            'salary' => $salary,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="back_salary_delete", methods={"POST"})
     */
    public function delete(Request $request, Salary $salary, SalaryRepository $salaryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$salary->getId(), $request->request->get('_token'))) {
            
            $this->addFlash('danger', 'Tranche de salaire : '. $salary->getName() .' supprimée.');
            $salaryRepository->remove($salary, true);
        }

        return $this->redirectToRoute('back_salary_index', [], Response::HTTP_SEE_OTHER);
    }
}
