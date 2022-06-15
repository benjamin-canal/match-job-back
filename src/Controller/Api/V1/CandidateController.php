<?php

namespace App\Controller\Api\V1;

use App\Repository\CandidateRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class that manages ressources of type Candidate
 * 
 * @Route("/api/v1", name="api_v1_")
 */
class CandidateController extends AbstractController
{
    /**
     * Method to have all candidates
     * 
     * @Route("/candidates", name="candidates", methods={"GET"})
     */
    public function candidatesGetCollection(CandidateRepository $candidateRepository): JsonResponse
    {
        
        $candidatesList = $candidateRepository->findAll();

        return $this->json([
            'candidates' => $candidatesList,
        ],
        Response::HTTP_OK,
        [],
        ['groups' => 'candidates_get_collection']
        );
    }
}
