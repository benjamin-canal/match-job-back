<?php

namespace App\Controller\Api\V1;

use App\Entity\Job;
use App\Entity\Recruiter;
use App\Repository\CandidateRepository;
use App\Repository\JobRepository;
use App\Repository\RecruiterRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class that manages ressources of type Recruiter
 * 
 * @Route("/api/v1", name="api_v1_")
 */
class RecruiterController extends AbstractController
{
    /**
     * Method to have all recruiters
     * 
     * @Route("/recruiters", name="recruiters", methods={"GET"} )
     */
    public function recruitersGetCollection(RecruiterRepository $recruiterRepository): JsonResponse
    {
        $recruitersList = $recruiterRepository->findAll();

        return $this->json([
            'recruiters' => $recruitersList,
        ],
        Response::HTTP_OK,
        [],
        ['groups' => 'recruiters_get_collection']
        );
    }

    /**
     * Method to have a recruiter whose {id} is given
     * 
     * @Route("/recruiters/{id}", name="recruiter_get_details", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function recruitersGetDetails(Recruiter $recruiter = null)
    {
        // 404 ?
        if ($recruiter === null) {
            // Returns an error if the Recruiter is unknown
            return $this->json(['error' => 'Recruiter souhaité : non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($recruiter, Response::HTTP_OK, [], ['groups' => 'recruiters_get_item']);
    }


    /**
     * Method to add a recruiter
     * 
     * @Route("/recruiters", name="recruiters_add", methods={"POST"})
     */
    public function recruitersAdd(
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ) {
        // We need to retrieve the JSON content from the Request
        $jsonContent = $request->getContent();

        // Deserialize the JSON content into a Recruiter entity
        $recruiter = $serializer->deserialize($jsonContent, Recruiter::class, 'json');

        // Validation of the entity
        // @link https://symfony.com/doc/current/validation.html#using-the-validator-service
        $errors = $validator->validate($recruiter);

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
        $em->persist($recruiter);
        $em->flush();

        // We return a response that contains (REST !)
        return $this->json(
            // Recruiter added
            $recruiter,
            // status code : 201 CREATED
            Response::HTTP_CREATED,
            // REST require locatiion header+ the URL of the created resource
            [
                'Location' => $this->generateUrl('api_v1_recruiter_get_details', ['id' => $recruiter->getId()])
            ],
            ['groups' => 'recruiters_get_item']
        );
    }


    /**
     * Method to update a Recruiter whose {id} is given
     * 
     * @Route("/recruiters/{id}", name="recruiters_update", methods={"PUT"}, requirements={"id"="\d+"})
     */
    public function recruitersUpdate(
        Recruiter $recruiter = null,
        RecruiterRepository $recruiterRepository,
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ) {
        
        // 404 ?
        if ($recruiterRepository === null) {
            // Returns an error if the Recruiter is unknown
            return $this->json(['error' => 'Recruiter souhaité : non trouvé.'], Response::HTTP_NOT_FOUND);
        }
        
        // We need to retrieve the JSON content from the Request
        $jsonContent = $request->getContent();

        // Deserialize the JSON content into a Recruiter entity
        $recruiterReceived = $serializer->deserialize($jsonContent, Recruiter::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $recruiter]);

        // Validation of the entity
        // @link https://symfony.com/doc/current/validation.html#using-the-validator-service
        $errors = $validator->validate($recruiterReceived);

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

        // update of information between the current entity and the received entity
        $em->flush();

        // We return a response that contains (REST !)
        
        return $this->json(
            // Recruiter updated
            $recruiter,
            // status code : 201 CREATED
            Response::HTTP_OK,
            // REST require locatiion header+ the URL of the created resource
            [
                'Location' => $this->generateUrl('api_v1_recruiter_get_details', ['id' => $recruiter->getId()])
            ],
            ['groups' => 'recruiters_get_item']
        );
    }


    /**
     * Method to remove a Recruiter whose {id} is given
     * 
     * @Route("/recruiters/{id}", name="recruiter_delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function recruitersDelete(Recruiter $recruiter = null, ManagerRegistry $doctrine)
    {
        // 404 ?
        if ($recruiter === null) {
            return $this->json(['error' => 'Titre de l\'emploi souhaité : non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $em = $doctrine->getManager();
        $em->remove($recruiter);
        $em->flush();

        return $this->json($recruiter, Response::HTTP_OK, [], ['groups' => 'recruiters_get_item']);
    }

    /**
     * Method to find all jobs that have matched for a candidate whose {id} is given
     * 
     * @Route("/recruiters/{id}/jobs/match", name="recruiter_get_jobs_matched", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function recruitersGetAllJobsMatched(Recruiter $recruiter = null, JobRepository $jobRepository)
    {
        // 404 ?
        if ($recruiter === null) {
            // Returns an error if the recruiter is unknown
            return $this->json(['error' => 'Recruteur non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $jobsList = $jobRepository->findAllJobsForRecruiterMatched($recruiter);

        return $this->json($jobsList, Response::HTTP_OK, [], ['groups' => 'jobs_get_item']);
    }

    /**
     * Method to retrieve all jobs interested
     * 
     * @Route("/recruiters/{id}/jobs/interested", name="recruiter_get_jobs_interested", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function recruitersGetAllJobsInterested(Recruiter $recruiter = null, JobRepository $jobRepository)
    {
        // 404 ?
        if ($recruiter === null) {
            // Returns an error if the recruiter is unknown
            return $this->json(['error' => 'Pas de recruteur trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $jobsList = $jobRepository->findAllJobsForRecruiterInterrested($recruiter);

        return $this->json($jobsList, Response::HTTP_OK, [], ['groups' => 'jobs_get_item']);
    }

    /**
     * Method to retrieve all jobs interested
     * 
     * @Route("/recruiters/jobs/{id}/candidates-interested", name="get_candidates_interested_by_job", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function recruitersGetAllCandidatesInterestedByJob(Job $job = null, CandidateRepository $candidateRepository)
    {
        // 404 ?
        if ($job === null) {
            // Returns an error if the job is unknown
            return $this->json(['error' => 'Pas d\'offre d\'emploi trouvée.'], Response::HTTP_NOT_FOUND);
        }

        $candidatesList = $candidateRepository->findAllCandidatesInterrestedByJob($job);

        return $this->json($candidatesList, Response::HTTP_OK, [], ['groups' => 'candidates_get_item']);
    }
}
