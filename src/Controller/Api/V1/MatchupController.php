<?php

namespace App\Controller\Api\V1;

use App\Entity\Matchup;
use App\Repository\CandidateRepository;
use App\Repository\JobRepository;
use App\Repository\JobtitleRepository;
use App\Repository\MatchupRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class that manages ressources of type Matchup
 * 
 * @Route("/api/v1", name="api_v1_")
 */
class MatchupController extends AbstractController
{
    /**
     * Method to have all matchups
     * 
     * @Route("/matchups", name="matchups", methods={"GET"})
     */
    public function matchupsGetCollection(MatchupRepository $matchupRepository): JsonResponse
    {
        
        $matchupsList = $matchupRepository->findAll();

        return $this->json([
            'matchups' => $matchupsList,
        ],
        Response::HTTP_OK,
        [],
        ['groups' => 'matchups_get_item']
        );
    }

    /**
     * Method to have a matchup whose {id} is given
     * 
     * @Route("/matchups/{id}", name="matchup_get_details", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function matchupsGetProfil(Matchup $matchup = null)
    {
        // 404 ?
        if ($matchup === null) {
            // Returns an error if the matchup is unknown
            return $this->json(['error' => 'Match non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($matchup, Response::HTTP_OK, [], ['groups' => 'matchups_get_item']);
    }

    // /**
    //  * Method to add a matchup
    //  * 
    //  * @Route("/jobs/candidate-interrested", name="matchups_add", methods={"POST"})
    //  */
    // public function matchupsAdd(
    //     Request $request,
    //     SerializerInterface $serializer,
    //     ManagerRegistry $doctrine,
    //     ValidatorInterface $validator
    // ) {
    //     // We need to retrieve the JSON content from the Request
    //     $jsonContent = $request->getContent();

    //     // Deserialize the JSON content into a Matchup entity
    //     $matchup = $serializer->deserialize($jsonContent, Matchup::class, 'json');

    //     // Validation of the entity
    //     // @link https://symfony.com/doc/current/validation.html#using-the-validator-service
    //     $errors = $validator->validate($matchup);

    //     if (count($errors) > 0) {

    //         $cleanErrors = [];

    //         /** @var ConstraintViolation $error */
    //         foreach ($errors as $error) {

    //             // retrieving information
    //             $property = $error->getPropertyPath();
    //             $message = $error->getMessage();

    //             // We add the message in an array to the key $property
    //             // PHP itself manages the existence of the second array
    //             $cleanErrors[$property][] = $message;
    //         }

    //         return $this->json($cleanErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
    //     }

    //     // backup in database
    //     $em = $doctrine->getManager();
    //     $matchup->setCandidateStatus(true);
    //     $matchup->setRecruiterStatus(false);
    //     if($matchup->getCandidateStatus() === true && $matchup->getRecruiterStatus() === true){
    //         $matchup->setMatchStatus(true);
    //     }else{
    //         $matchup->setMatchStatus(false);
    //     };

    //     $em->persist($matchup);
    //     $em->flush();

    //     // We return a response that contains (REST !)
    //     return $this->json(
    //         // matchup added
    //         $matchup,
    //         // status code : 201 CREATED
    //         Response::HTTP_CREATED,
    //         // REST require locatiion header+ the URL of the created resource
    //         [
    //             'Location' => $this->generateUrl('api_v1_matchup_get_details', ['id' => $matchup->getId()])
    //         ],
    //         ['groups' => 'matchups_get_item']
    //     );
    // }

    /**
     * Method for managing a candidate's interest for a job
     * 
     * @Route("/jobs/candidate-interrested", name="candidate_interrested_by_job", methods={"PUT"})
     */
    public function candidateInterrestedByJob(
        MatchupRepository $matchupRepository,
        Request $request,
        ManagerRegistry $doctrine,
        CandidateRepository $candidateRepository,
        JobRepository $jobRepository
    ) {
                
        // We need to retrieve the JSON content from the Request
        $jsonContent = $request->getContent();

        // Decode the JSON content
        $matchupReceived = json_decode($jsonContent, true);
        $jobId = $matchupReceived['job']['id'];
        $candidateId = $matchupReceived['candidate']['id'];

        //we check if there is already a matchup
        // find matchup by job_id and candidate_id
        $matchup = $matchupRepository->findOneMatchupBySomeFields($jobId, $candidateId);
        
        if (!$matchup){
            // we create a new matchup
            $matchup = new Matchup;
            $matchup->setCandidate($candidateRepository->find($candidateId));
            $matchup->setJob($jobRepository->find($jobId));
            $matchup->setCandidateStatus(true);

        } else {
            // modification of the matchup because there is a match
            $matchup->setCandidateStatus(true);
                    
            if($matchup->getCandidateStatus() === true && $matchup->getRecruiterStatus() === true){
                $matchup->setMatchStatus(true);
            };
        }

        // dd($matchup, $newMatchup);

        // backup in database
        $em = $doctrine->getManager();        
        $em->persist($matchup);
        $em->flush();

        // We return a response that contains (REST !)
        
        return $this->json(
            // matchup updated
            $matchup,
            // status code : 201 CREATED
            Response::HTTP_OK,
            // REST require locatiion header+ the URL of the created resource
            [
                'Location' => $this->generateUrl('api_v1_matchup_get_details', ['id' => $matchup->getId()])
            ],
            ['groups' => 'matchups_get_item']
        );
    }

    /**
     * Method to manage the recruiter's interest for a candidate (via the job)
     * 
     * @Route("/jobs/recruiter-interrested", name="recruiter_interrested_by_candidate", methods={"PUT"})
     */
    public function matchupsMatched(
        MatchupRepository $matchupRepository,
        Request $request,
        ManagerRegistry $doctrine,
        CandidateRepository $candidateRepository,
        JobRepository $jobRepository
    ) {
                
        // We need to retrieve the JSON content from the Request
        $jsonContent = $request->getContent();

        // Decode the JSON content
        $matchupReceived = json_decode($jsonContent, true);
        $jobId = $matchupReceived['job']['id'];
        $candidateId = $matchupReceived['candidate']['id'];

        //we check if there is already a matchup
        // find matchup by job_id and candidate_id
        $matchup = $matchupRepository->findOneMatchupBySomeFields($jobId, $candidateId);

        
        if (!$matchup){
            // we create a new matchup
            $matchup = new Matchup;
            $matchup->setCandidate($candidateRepository->find($candidateId));
            $matchup->setJob($jobRepository->find($jobId));
            $matchup->setRecruiterStatus(true);

        } else {
            // modification of the matchup because there is a match
            $matchup->setRecruiterStatus(true);
                    
            if($matchup->getCandidateStatus() === true && $matchup->getRecruiterStatus() === true){
                $matchup->setMatchStatus(true);
            };
        }

        // dd($matchup, $newMatchup);

        // backup in database
        $em = $doctrine->getManager();        
        $em->persist($matchup);
        $em->flush();

        // We return a response that contains (REST !)
        
        return $this->json(
            // matchup updated
            $matchup,
            // status code : 201 CREATED
            Response::HTTP_OK,
            // REST require locatiion header+ the URL of the created resource
            [
                'Location' => $this->generateUrl('api_v1_matchup_get_details', ['id' => $matchup->getId()])
            ],
            ['groups' => 'matchups_get_item']
        );
    }

    /**
     * Method to remove a matchup whose {id} is given
     * 
     * @Route("/matchups/{id}", name="matchup_delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function matchupsDelete(Matchup $matchup = null, ManagerRegistry $doctrine)
    {
        // 404 ?
        if ($matchup === null) {
            return $this->json(['error' => 'Match non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $em = $doctrine->getManager();
        $em->remove($matchup);
        $em->flush();

        return $this->json($matchup, Response::HTTP_OK, [], ['groups' => 'matchups_get_item']);
    }
}

