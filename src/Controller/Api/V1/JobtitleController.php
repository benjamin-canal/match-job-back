<?php

namespace App\Controller\Api\V1;

use App\Entity\Jobtitle;
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
 * Class that manages ressources of type Jobtitle
 * 
 * @Route("/api/v1", name="api_v1_")
 */
class JobtitleController extends AbstractController
{
    /**
     * Method to have all jobtitles
     * 
     * @Route("/jobtitles", name="jobtitles", methods={"GET"})
     */
    public function jobtitlesGetCollection(JobtitleRepository $jobtitleRepository): JsonResponse
    {
        
        $jobtitlesList = $jobtitleRepository->findAll();

        return $this->json([
            'jobtitles' => $jobtitlesList,
        ],
        Response::HTTP_OK,
        [],
        ['groups' => 'jobtitles_get_item']
        );
    }

    /**
     * Method to have a jobtitle whose {id} is given
     * 
     * @Route("/jobtitles/{id}", name="jobtitle_get_details", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function jobtitlesGetProfil(Jobtitle $jobtitle = null)
    {
        // 404 ?
        if ($jobtitle === null) {
            // Returns an error if the jobtitle is unknown
            return $this->json(['error' => 'Titre de l\'emploi souhaité : non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($jobtitle, Response::HTTP_OK, [], ['groups' => 'jobtitles_get_item']);
    }

    /**
     * Method to add a jobtitle
     * 
     * @Route("/jobtitles", name="jobtitles_add", methods={"POST"})
     */
    public function jobtitlesAdd(
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ) {
        // We need to retrieve the JSON content from the Request
        $jsonContent = $request->getContent();

        // Deserialize the JSON content into a Jobtitle entity
        $jobtitle = $serializer->deserialize($jsonContent, Jobtitle::class, 'json');

        // Validation of the entity
        // @link https://symfony.com/doc/current/validation.html#using-the-validator-service
        $errors = $validator->validate($jobtitle);

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
        $em->persist($jobtitle);
        $em->flush();

        // We return a response that contains (REST !)
        return $this->json(
            // jobtitle added
            $jobtitle,
            // status code : 201 CREATED
            Response::HTTP_CREATED,
            // REST require location header+ the URL of the created resource
            [
                'Location' => $this->generateUrl('api_v1_jobtitle_get_details', ['id' => $jobtitle->getId()])
            ],
            ['groups' => 'jobtitles_get_item']
        );
    }

    /**
     * Method to update a jobtitle whose {id} is given
     * 
     * @Route("/jobtitles/{id}", name="jobtitles_update", methods={"PUT"}, requirements={"id"="\d+"})
     */
    public function jobtitlesUpdate(
        Jobtitle $jobtitle = null,
        JobtitleRepository $jobtitleRepository,
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ) {
        
        // 404 ?
        if ($jobtitleRepository === null) {
            // Returns an error if the jobtitle is unknown
            return $this->json(['error' => 'Titre de l\'emploi souhaité : non trouvé.'], Response::HTTP_NOT_FOUND);
        }
        
        // We need to retrieve the JSON content from the Request
        $jsonContent = $request->getContent();

        // Deserialize the JSON content into a Jobtitle entity
        $jobtitleReceived = $serializer->deserialize($jsonContent, Jobtitle::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $jobtitle]);

        // Validation of the entity
        // @link https://symfony.com/doc/current/validation.html#using-the-validator-service
        $errors = $validator->validate($jobtitleReceived);

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
            $jobtitle,
            // status code : 201 CREATED
            Response::HTTP_OK,
            // REST require location header+ the URL of the created resource
            [
                'Location' => $this->generateUrl('api_v1_jobtitle_get_details', ['id' => $jobtitle->getId()])
            ],
            ['groups' => 'jobtitles_get_item']
        );
    }

    /**
     * Method to remove a jobtitle whose {id} is given
     * 
     * @Route("/jobtitles/{id}", name="jobtitle_delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function jobtitlesDelete(Jobtitle $jobtitle = null, ManagerRegistry $doctrine)
    {
        // 404 ?
        if ($jobtitle === null) {
            return $this->json(['error' => 'Titre de l\'emploi souhaité : non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $em = $doctrine->getManager();
        $em->remove($jobtitle);
        $em->flush();

        return $this->json($jobtitle, Response::HTTP_OK, [], ['groups' => 'jobtitles_get_item']);
    }
}

