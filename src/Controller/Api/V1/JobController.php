<?php

namespace App\Controller\Api\V1;

use App\Entity\Candidate;
use App\Entity\Job;
use App\Entity\Jobtitle;
use App\Repository\JobRepository;
use App\Repository\JobtitleRepository;
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
 * Class that manages ressources of type Job
 * 
 * @Route("/api/v1", name="api_v1_")
 */
class JobController extends AbstractController
{   
    /**
     * Method to have all jobtitles
     * 
     * @Route("/jobs", name="jobs", methods={"GET"} )
     */
    public function jobsGetCollection(JobRepository $jobRepository): JsonResponse
    {
        $jobsList = $jobRepository->findAll();

        return $this->json([
            'jobs' => $jobsList,
        ],
        Response::HTTP_OK,
        [],
        ['groups' => 'jobs_get_collection']
        );
    }

    /**
     * Method to have a jobtitle whose {id} is given
     * 
     * @Route("/jobs/{id}", name="job_get_details", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function jobsGetDetails(Job $job = null)
    {
        // 404 ?
        if ($job === null) {
            // Returns an error if the jobtitle is unknown
            return $this->json(['error' => 'Job souhaité : non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($job, Response::HTTP_OK, [], ['groups' => 'jobs_get_item']);
    }


    /**
     * Method to add a job
     * 
     * @Route("/jobs", name="jobs_add", methods={"POST"})
     */
    public function jobsAdd(
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ) {
        // We need to retrieve the JSON content from the Request
        $jsonContent = $request->getContent();

        // Deserialize the JSON content into a Jobtitle entity
        $job = $serializer->deserialize($jsonContent, Job::class, 'json');

        // Validation of the entity
        // @link https://symfony.com/doc/current/validation.html#using-the-validator-service
        $errors = $validator->validate($job);

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
        $em->persist($job);
        $em->flush();

        // We return a response that contains (REST !)
        return $this->json(
            // jobtitle added
            $job,
            // status code : 201 CREATED
            Response::HTTP_CREATED,
            // REST require locatiion header+ the URL of the created resource
            [
                'Location' => $this->generateUrl('api_v1_job_get_details', ['id' => $job->getId()])
            ],
            ['groups' => 'jobs_get_item']
        );
    }


    /**
     * Method to update a job whose {id} is given
     * 
     * @Route("/jobs/{id}", name="jobs_update", methods={"PUT"}, requirements={"id"="\d+"})
     */
    public function jobsUpdate(
        Jobtitle $job = null,
        JobtitleRepository $jobtitleRepository,
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ) {
        
        // 404 ?
        if ($jobtitleRepository === null) {
            // Returns an error if the jobtitle is unknown
            return $this->json(['error' => 'Job souhaité : non trouvé.'], Response::HTTP_NOT_FOUND);
        }
        
        // We need to retrieve the JSON content from the Request
        $jsonContent = $request->getContent();

        // Deserialize the JSON content into a Jobtitle entity
        $jobReceived = $serializer->deserialize($jsonContent, Job::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $job]);

        // Validation of the entity
        // @link https://symfony.com/doc/current/validation.html#using-the-validator-service
        $errors = $validator->validate($jobReceived);

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
            // jobtitle updated
            $job,
            // status code : 201 CREATED
            Response::HTTP_OK,
            // REST require locatiion header+ the URL of the created resource
            [
                'Location' => $this->generateUrl('api_v1_job_get_details', ['id' => $job->getId()])
            ],
            ['groups' => 'jobs_get_item']
        );
    }


    /**
     * Method to remove a job whose {id} is given
     * 
     * @Route("/jobs/{id}", name="job_delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function jobsDelete(Job $job = null, ManagerRegistry $doctrine)
    {
        // 404 ?
        if ($job === null) {
            return $this->json(['error' => 'Titre de l\'emploi souhaité : non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $em = $doctrine->getManager();
        $em->remove($job);
        $em->flush();

        return $this->json($job, Response::HTTP_OK, [], ['groups' => 'jobs_get_item']);
    }

    /**
     * Method to get all jobs possible to matched with candidate
     * 
     * @Route("/jobs/possible-match-candidate/{id}", name="jobs_possible_matched_with_candidate", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function jobsGetPossibleMatchedCandidate(
        Candidate $candidate = null,
        JobRepository $jobRepository,
        Request $request
        ): JsonResponse
    {
        
        // 404 ?
        if ($candidate === null) {
            // Returns an error if the candidate is unknown
            return $this->json(['error' => 'Candidat non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        // We need to retrieve the JSON content from the Request
        $jsonContent = $request->getContent();
        
        // Decode the JSON content
        if ($jsonContent != ""){
            $options = json_decode($jsonContent, true)['options'][0];
        } else {
            $options = [
                'contract' => true,
                'experience' => true,
                'jobtitle' => true,
                'salary' => true
            ];
        }        
        
        // find all jobs that match with the candidate
        $jobsList= $jobRepository->findAllJobsPossibleMatchedWithCandidate($candidate, $options);

               
        return $this->json([
            'jobs' => $jobsList,
        ],
        Response::HTTP_OK,
        [],
        ['groups' => 'jobs_get_collection']
        );
    }

}
