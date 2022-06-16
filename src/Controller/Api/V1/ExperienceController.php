<?php

namespace App\Controller\Api\V1;

use App\Entity\Experience;
use App\Repository\ExperienceRepository;
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
 * Class that manages ressources of type Experience
 * 
 * @Route("/api/v1", name="api_v1_")
 */
class ExperienceController extends AbstractController
{
    /**
     * Method to have all experiences
     * 
     * @Route("/experiences", name="experiences", methods={"GET"})
     */
    public function experiencesGetCollection(ExperienceRepository $experienceRepository): JsonResponse
    {
        
        $experiencesList = $experienceRepository->findAll();

        return $this->json([
            'experiences' => $experiencesList,
        ],
        Response::HTTP_OK,
        [],
        ['groups' => 'experiences_get_item']
        );
    }

    /**
     * Method to have a experience whose {id} is given
     * 
     * @Route("/experiences/{id}", name="experience_get_details", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function experiencesGetProfil(Experience $experience = null)
    {
        // 404 ?
        if ($experience === null) {
            // Returns an error if the experience is unknown
            return $this->json(['error' => 'Expérience non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($experience, Response::HTTP_OK, [], ['groups' => 'experiences_get_item']);
    }

    /**
     * Method to add a experience
     * 
     * @Route("/experiences", name="experiences_add", methods={"POST"})
     */
    public function experiencesAdd(
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ) {
        // We need to retrieve the JSON content from the Request
        $jsonContent = $request->getContent();

        // Deserialize the JSON content into a Experience entity
        $experience = $serializer->deserialize($jsonContent, Experience::class, 'json');

        // Validation of the entity
        // @link https://symfony.com/doc/current/validation.html#using-the-validator-service
        $errors = $validator->validate($experience);

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
        $em->persist($experience);
        $em->flush();

        // We return a response that contains (REST !)
        return $this->json(
            // experience added
            $experience,
            // status code : 201 CREATED
            Response::HTTP_CREATED,
            // REST require locatiion header+ the URL of the created resource
            [
                'Location' => $this->generateUrl('api_v1_experience_get_details', ['id' => $experience->getId()])
            ],
            ['groups' => 'experiences_get_item']
        );
    }

    /**
     * Method to update a experience whose {id} is given
     * 
     * @Route("/experiences/{id}", name="experiences_update", methods={"PUT"}, requirements={"id"="\d+"})
     */
    public function experiencesUpdate(
        Experience $experience = null,
        ExperienceRepository $experienceRepository,
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ) {
        
        // 404 ?
        if ($experienceRepository === null) {
            // Returns an error if the experience is unknown
            return $this->json(['error' => 'Expérience non trouvée.'], Response::HTTP_NOT_FOUND);
        }
        
        // We need to retrieve the JSON content from the Request
        $jsonContent = $request->getContent();

        // Deserialize the JSON content into a Experience entity
        $userReceived = $serializer->deserialize($jsonContent, Experience::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $experience]);

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
            // experience updated
            $experience,
            // status code : 201 CREATED
            Response::HTTP_OK,
            // REST require locatiion header+ the URL of the created resource
            [
                'Location' => $this->generateUrl('api_v1_experience_get_details', ['id' => $experience->getId()])
            ],
            ['groups' => 'experiences_get_item']
        );
    }

    /**
     * Method to remove a experience whose {id} is given
     * 
     * @Route("/experiences/{id}", name="experience_delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function experiencesDelete(Experience $experience = null, ManagerRegistry $doctrine)
    {
        // 404 ?
        if ($experience === null) {
            return $this->json(['error' => 'Expérience non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        $em = $doctrine->getManager();
        $em->remove($experience);
        $em->flush();

        return $this->json($experience, Response::HTTP_OK, [], ['groups' => 'experiences_get_item']);
    }
}

