<?php

namespace App\Controller\Api\V1;

use App\Entity\Job;
use App\Entity\Candidate;
use App\Service\FileUploader;
use App\Repository\JobRepository;
use App\Repository\MatchupRepository;
use App\Repository\CandidateRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Bridge\Doctrine\ManagerRegistry as DoctrineManagerRegistry;
use Symfony\Component\Filesystem\Filesystem;

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

    /**
     * Method to update a candidate whose {id} is given
     * 
     * @Route("/candidates/{id}", name="candidates_update", methods={"PUT"}, requirements={"id"="\d+"})
     */
    public function candidatesUpdate(
        Candidate $candidate = null,
        CandidateRepository $candidateRepository,
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ) {
        
        // 404 ?
        if ($candidateRepository === null) {
            // Returns an error if the candidate is unknown
            return $this->json(['error' => 'Candidat non trouvé.'], Response::HTTP_NOT_FOUND);
        }
        
        // We need to retrieve the JSON content from the Request
        $jsonContent = $request->getContent();

        // Deserialize the JSON content into a Candidate entity
        $userReceived = $serializer->deserialize($jsonContent, Candidate::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $candidate]);

        // Validation of the entity
        // @link https://symfony.com/doc/current/validation.html#using-the-validator-service
        $errors = $validator->validate($userReceived);

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
            // candidate updated
            $candidate,
            // status code : 201 CREATED
            Response::HTTP_OK,
            // REST require locatiion header+ the URL of the created resource
            [
                'Location' => $this->generateUrl('api_v1_candidate_get_profil', ['id' => $candidate->getId()])
            ],
            ['groups' => 'candidates_get_item']
        );
    }

    /**
     * Method to remove a candidate whose {id} is given
     * 
     * @Route("/candidates/{id}", name="candidate_delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function candidatesDelete(Candidate $candidate = null, ManagerRegistry $doctrine)
    {
        // 404 ?
        if ($candidate === null) {
            return $this->json(['error' => 'Candidat non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $em = $doctrine->getManager();
        $em->remove($candidate);
        $em->flush();

        return $this->json($candidate, Response::HTTP_OK, [], ['groups' => 'candidates_get_item']);
    }

    /**
     * Method to find all jobs that have matched for a candidate whose {id} is given
     * 
     * @Route("/candidates/{id}/jobs/match", name="candidate_get_jobs_matched", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function candidatesGetAllJobsMatched(Candidate $candidate = null, MatchupRepository $matchupRepository, JobRepository $jobRepository)
    {
        // 404 ?
        if ($candidate === null) {
            // Returns an error if the candidate is unknown
            return $this->json(['error' => 'Candidat non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        // dd($matchupRepository->findAllMatchedJobs($candidate));
        // dd($jobRepository->findAllJobsMatched($candidate));

        $matchups = $matchupRepository->findAllMatchedJobs($candidate);
        $jobsList = $jobRepository->findAllJobsForCandidateMatched($candidate);

        return $this->json($jobsList, Response::HTTP_OK, [], ['groups' => 'jobs_get_item']);
    }

    /**
     * Method to retrieve all jobs interested by the candidate
     * 
     * @Route("/candidates/{id}/jobs/interested", name="candidate_get_jobs!interested", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function candidatesGetAllJobsInterested(Candidate $candidate = null, JobRepository $jobRepository)
    {
        // 404 ?
        if ($candidate === null) {
            // Returns an error if the candidate is unknown
            return $this->json(['error' => 'Pas de candidat trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $jobsList = $jobRepository->findAllJobsForCandidateInterested($candidate);

        return $this->json($jobsList, Response::HTTP_OK, [], ['groups' => 'jobs_get_item']);
    }

    /**
     * Method to retrieve all jobs for which the recruiter is interested in the candidate's profile
     * 
     * @Route("/candidates/{id}/jobs/interested_recruiter", name="candidate_get_jobs_interested", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function candidatesGetAllJobsInterestedByTheRecruiter(Candidate $candidate = null, JobRepository $jobRepository)
    {
        // 404 ?
        if ($candidate === null) {
            // Returns an error if the candidate is unknown
            return $this->json(['error' => 'Pas de candidat trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $jobsList = $jobRepository->findAllJobsForCandidateInterestedByRecruiter($candidate);

        return $this->json($jobsList, Response::HTTP_OK, [], ['groups' => 'jobs_get_item']);
    }

    /**
     * Method to get all candidates possible to matched with job
     * 
     * @Route("/candidates/possible-match-job/{id}", name="candidates_possible_matched_with_job", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function CandidatesGetPossibleMatchedJob(
        Job $job = null,
        CandidateRepository $candidateRepository, 
        Request $request
    ) {
        // 404 ?
        if ($job === null) {
            // Returns an error if the job is unknown
            return $this->json(['error' => 'Pas d\'offre d\'emploi trouvée.'], Response::HTTP_NOT_FOUND);
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

        $candidatesList = $candidateRepository->findAllCandidatesPossibleMatchedWithJob($job, $options);

        return $this->json($candidatesList, Response::HTTP_OK, [], ['groups' => 'candidates_get_collection']);
    }

        
    /**
     * Method to add and update a picture for a given candidate
     * 
    * @Route("/candidates/{id}/pictures", methods={"POST"}, name="candidates_add_picture", requirements={"id"="\d+"})
    */
    public function addCandidatesPicture(Request $request, 
    ValidatorInterface $validator, 
    FileUploader $fileUploader, 
    Candidate $candidate = null, 
    ManagerRegistry $doctrine) 
    {   
        // We get the picture file name of the $candidate in the BDD
        $picture = $candidate->getPicture();
        $pictureToRemove = $fileUploader->getTargetDirectory() . '/' . $picture;

        //If there is an existing picture we remove it
        if (file_exists($pictureToRemove)) {
            $fileSysteme = new Filesystem();
        $fileSysteme->remove($pictureToRemove);
        }

        // We use the request object to get the picture
        $uploadedFile = $request->files->get('picture');

        // Exception error 400 if picture is missing
        if (!$uploadedFile) {
            throw new BadRequestHttpException('Fichier image requis.');
        }

        // File validator
        $errors = $validator->validate($uploadedFile, [
            // Image constraint
            new Image([
            'maxSize' => '1024k',
            ]),
        ]);

        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        if ($uploadedFile) {
            $uploadedFileName = $fileUploader->upload($uploadedFile);
            // 404 ?
        if ($candidate === null) {
            return $this->json(['error' => 'Candidat non trouvé.'], Response::HTTP_NOT_FOUND);
        }
        
        // add or update image
        $candidate->setPicture($uploadedFileName);
        $em = $doctrine->getManager();
        $em->persist($candidate);
        $em->flush();
            
        }

        return $this->json($uploadedFileName, Response::HTTP_CREATED);

    }

    /**
     * Method to get a picture for a given candidate
     * 
    * @Route("/candidates/{id}/pictures", methods={"GET"}, name="candidates_get_picture", requirements={"id"="\d+"})
    */
    public function getCandidatesPicture(Candidate $candidate = null) : Response
    {
        // 404 ?
        if ($candidate === null) {
            // Return an error if the $candidate is unknown
            return $this->json(['error' => 'Candidat non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        // We get the $candidate picture name in the BDD
        $pictureName = $candidate->getPicture();
        
        // We get the path to te directory where the $candidate picture was uploaded with the same name stored in BDD
        $pictureFile = $this->getParameter('kernel.project_dir') . '/pictures/' . $pictureName;

        // This variable is create to compare the name in the uploade directory and in the BDD
        $pictureFileName = $this->getParameter('kernel.project_dir') . '/pictures/' . $pictureName;

        // New BinaryFileResponse returned if there is no errors
        if(file_exists($pictureFile) && ($pictureFile = $pictureFileName)) {
            return new BinaryFileResponse($pictureFile);
        } 

        // Else json error is returned
        return $this->json(['error' => 'Image non trouvée.'], Response::HTTP_NOT_FOUND); 
        
    }

}
