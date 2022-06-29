<?php

namespace App\Controller\Api\V1;

use App\Entity\Adress;
use App\Repository\AdressRepository;
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
 * Class that manages ressources of type Adress
 * 
 * @Route("/api/v1", name="api_v1_")
 */
class AdressController extends AbstractController
{
    /**
     * Method to have all adresses
     * 
     * @Route("/adresses", name="adresses", methods={"GET"})
     */
    public function adressesGetCollection(AdressRepository $adressRepository): JsonResponse
    {
        
        $adressesList = $adressRepository->findAll();

        return $this->json([
            'adresses' => $adressesList,
        ],
        Response::HTTP_OK,
        [],
        ['groups' => 'adresses_get_item']
        );
    }

    /**
     * Method to have a adress whose {id} is given
     * 
     * @Route("/adresses/{id}", name="adress_get_details", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function adressesGetProfil(Adress $adress = null)
    {
        // 404 ?
        if ($adress === null) {
            // Returns an error if the adress is unknown
            return $this->json(['error' => 'Adresse non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($adress, Response::HTTP_OK, [], ['groups' => 'adresses_get_item']);
    }

    /**
     * Method to add a adress
     * 
     * @Route("/adresses", name="adresses_add", methods={"POST"})
     */
    public function adressesAdd(
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ) {
        // We need to retrieve the JSON content from the Request
        $jsonContent = $request->getContent();

        // Deserialize the JSON content into a Adress entity
        $adress = $serializer->deserialize($jsonContent, Adress::class, 'json');

        // Validation of the entity
        // @link https://symfony.com/doc/current/validation.html#using-the-validator-service
        $errors = $validator->validate($adress);

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
        $em->persist($adress);
        $em->flush();

        // We return a response that contains (REST !)
        return $this->json(
            // adress added
            $adress,
            // status code : 201 CREATED
            Response::HTTP_CREATED,
            // REST require location header+ the URL of the created resource
            [
                'Location' => $this->generateUrl('api_v1_adress_get_details', ['id' => $adress->getId()])
            ],
            ['groups' => 'adresses_get_item']
        );
    }

    /**
     * Method to update a adress whose {id} is given
     * 
     * @Route("/adresses/{id}", name="adresses_update", methods={"PUT"}, requirements={"id"="\d+"})
     */
    public function adressesUpdate(
        Adress $adress = null,
        AdressRepository $adressRepository,
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ) {
        
        // 404 ?
        if ($adressRepository === null) {
            // Returns an error if the adress is unknown
            return $this->json(['error' => 'Adresse non trouvée.'], Response::HTTP_NOT_FOUND);
        }
        
        // We need to retrieve the JSON content from the Request
        $jsonContent = $request->getContent();

        // Deserialize the JSON content into a Adress entity
        $adressReceived = $serializer->deserialize($jsonContent, Adress::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $adress]);

        // Validation of the entity
        // @link https://symfony.com/doc/current/validation.html#using-the-validator-service
        $errors = $validator->validate($adressReceived);

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
            // adress updated
            $adress,
            // status code : 201 CREATED
            Response::HTTP_OK,
            // REST require location header+ the URL of the created resource
            [
                'Location' => $this->generateUrl('api_v1_adress_get_details', ['id' => $adress->getId()])
            ],
            ['groups' => 'adresses_get_item']
        );
    }

    /**
     * Method to remove a adress whose {id} is given
     * 
     * @Route("/adresses/{id}", name="adress_delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function adressesDelete(Adress $adress = null, ManagerRegistry $doctrine)
    {
        // 404 ?
        if ($adress === null) {
            return $this->json(['error' => 'Adresse non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        $em = $doctrine->getManager();
        $em->remove($adress);
        $em->flush();

        return $this->json($adress, Response::HTTP_OK, [], ['groups' => 'adresses_get_item']);
    }
}

