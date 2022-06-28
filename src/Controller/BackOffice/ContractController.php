<?php

namespace App\Controller\BackOffice;

use App\Entity\Contract;
use App\Form\ContractType;
use App\Repository\ContractRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back/contract")
 */
class ContractController extends AbstractController
{
    /**
     * @Route("", name="back_contract_index", methods={"GET"})
     */
    public function index(ContractRepository $contractRepository): Response
    {
        return $this->render('contract/index.html.twig', [
            'contracts' => $contractRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="back_contract_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ContractRepository $contractRepository): Response
    {
        $contract = new Contract();
        $form = $this->createForm(ContractType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contractRepository->add($contract, true);

            $this->addFlash('success', 'Contrat : '. $contract->getName() .' ajouté.');
            return $this->redirectToRoute('back_contract_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contract/new.html.twig', [
            'contract' => $contract,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="back_contract_show", methods={"GET"})
     */
    public function show(Contract $contract): Response
    {
        return $this->render('contract/show.html.twig', [
            'contract' => $contract,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="back_contract_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Contract $contract, ContractRepository $contractRepository): Response
    {
        $form = $this->createForm(ContractType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contractRepository->add($contract, true);

            $this->addFlash('warning', 'Contrat : '. $contract->getName() .' modifié.');
            return $this->redirectToRoute('back_contract_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contract/edit.html.twig', [
            'contract' => $contract,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="back_contract_delete", methods={"POST"})
     */
    public function delete(Request $request, Contract $contract, ContractRepository $contractRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contract->getId(), $request->request->get('_token'))) {

            $this->addFlash('danger', 'Contrat : '. $contract->getName() .' supprimé.');
            $contractRepository->remove($contract, true);
        }

        return $this->redirectToRoute('back_contract_index', [], Response::HTTP_SEE_OTHER);
    }
}
