<?php

namespace App\Controller\Api\V1;

use App\Entity\Candidate;
use App\Repository\CandidateRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
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

    /**
     * Method to have a candidate whose {id} is given
     * 
     * @Route("/candidates/{id}", name="candidate_get_profil", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function candidatesGetProfil(Candidate $candidate = null)
    {
        // 404 ?
        if ($candidate === null) {
            // Returns an error if the candidate is unknown
            return $this->json(['error' => 'Candidat non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($candidate, Response::HTTP_OK, [], ['groups' => 'candidates_get_item']);
    }

    /**
     * Method to add a candidate
     * 
     * @Route("/candidates", name="candidates_add", methods={"POST"})
     */
    public function candidatesAdd(
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ) {
        // We need to retrieve the JSON content from the Request
        $jsonContent = $request->getContent();

        // Deserialize the JSON content into a Candidate entity
        $candidate = $serializer->deserialize($jsonContent, Candidate::class, 'json');

        // dd($candidate);

        // Validation of the entity
        // @link https://symfony.com/doc/current/validation.html#using-the-validator-service
        $errors = $validator->validate($candidate);

        if (count($errors) > 0) {

            $cleanErrors = [];

            /** @var ConstraintViolation $error */
            foreach ($errors as $error) {

                // retrieving information
                $property = $error->getPropertyPath();
                $message = $error->getMessage();

                // We add the message in an array to the key $property
                // PHP itself manages the existence of the second array
                $cleanErrors[$property][] = $message;
            }

            return $this->json($cleanErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // backup in database
        $em = $doctrine->getManager();
        $em->persist($candidate);
        $em->flush();

        // We return a response that contains (REST !)
        return $this->json(
            // candidate added
            $candidate,
            // status code : 201 CREATED
            Response::HTTP_CREATED,
            // REST require locatiion header+ the URL of the created resource
            [
                'Location' => $this->generateUrl('api_v1_candidate_get_profil', ['id' => $candidate->getId()])
            ],
            ['groups' => 'candidates_get_item']
        );
    }
}
