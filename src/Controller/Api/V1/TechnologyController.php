<?php

namespace App\Controller\Api\V1;

use App\Entity\Technology;
use App\Repository\TechnologyRepository;
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
 * Class that manages ressources of type Technology
 * 
 * @Route("/api/v1", name="api_v1_")
 */
class TechnologyController extends AbstractController
{
    /**
     * Method to have all technologies
     * 
     * @Route("/technologies", name="technologies", methods={"GET"})
     */
    public function technologiesGetCollection(TechnologyRepository $technologyRepository): JsonResponse
    {
        
        $technologiesList = $technologyRepository->findAll();

        return $this->json([
            'technologies' => $technologiesList,
        ],
        Response::HTTP_OK,
        [],
        ['groups' => 'technologies_get_item']
        );
    }

    /**
     * Method to have a technology whose {id} is given
     * 
     * @Route("/technologies/{id}", name="technology_get_details", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function technologiesGetProfil(Technology $technology = null)
    {
        // 404 ?
        if ($technology === null) {
            // Returns an error if the technology is unknown
            return $this->json(['error' => 'Technologie non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($technology, Response::HTTP_OK, [], ['groups' => 'technologies_get_item']);
    }

    /**
     * Method to add a technology
     * 
     * @Route("/technologies", name="technologies_add", methods={"POST"})
     */
    public function technologiesAdd(
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ) {
        // We need to retrieve the JSON content from the Request
        $jsonContent = $request->getContent();

        // Deserialize the JSON content into a Technology entity
        $technology = $serializer->deserialize($jsonContent, Technology::class, 'json');

        // Validation of the entity
        // @link https://symfony.com/doc/current/validation.html#using-the-validator-service
        $errors = $validator->validate($technology);

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
        $em->persist($technology);
        $em->flush();

        // We return a response that contains (REST !)
        return $this->json(
            // technology added
            $technology,
            // status code : 201 CREATED
            Response::HTTP_CREATED,
            // REST require location header + the URL of the created resource
            [
                'Location' => $this->generateUrl('api_v1_technology_get_details', ['id' => $technology->getId()])
            ],
            ['groups' => 'technologies_get_item']
        );
    }

    /**
     * Method to update a technology whose {id} is given
     * 
     * @Route("/technologies/{id}", name="technologies_update", methods={"PUT"}, requirements={"id"="\d+"})
     */
    public function technologiesUpdate(
        Technology $technology = null,
        TechnologyRepository $technologyRepository,
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ) {
        
        // 404 ?
        if ($technologyRepository === null) {
            // Returns an error if the technology is unknown
            return $this->json(['error' => 'Technologie non trouvée.'], Response::HTTP_NOT_FOUND);
        }
        
        // We need to retrieve the JSON content from the Request
        $jsonContent = $request->getContent();

        // Deserialize the JSON content into a Technology entity
        $technologyReceived = $serializer->deserialize($jsonContent, Technology::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $technology]);

        // Validation of the entity
        // @link https://symfony.com/doc/current/validation.html#using-the-validator-service
        $errors = $validator->validate($technologyReceived);

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
            // technology updated
            $technology,
            // status code : 201 CREATED
            Response::HTTP_OK,
            // REST require location header + the URL of the created resource
            [
                'Location' => $this->generateUrl('api_v1_technology_get_details', ['id' => $technology->getId()])
            ],
            ['groups' => 'technologies_get_item']
        );
    }

    /**
     * Method to remove a technology whose {id} is given
     * 
     * @Route("/technologies/{id}", name="technology_delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function technologiesDelete(Technology $technology = null, ManagerRegistry $doctrine)
    {
        // 404 ?
        if ($technology === null) {
            return $this->json(['error' => 'Technologie non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        $em = $doctrine->getManager();
        $em->remove($technology);
        $em->flush();

        return $this->json($technology, Response::HTTP_OK, [], ['groups' => 'technologies_get_item']);
    }
}

