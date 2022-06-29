<?php

namespace App\Controller\Api\V1;

use App\Entity\Sector;
use App\Repository\SectorRepository;
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
 * Class that manages ressources of type Sector
 * 
 * @Route("/api/v1", name="api_v1_")
 */
class SectorController extends AbstractController
{
    /**
     * Method to have all sectors
     * 
     * @Route("/sectors", name="sectors", methods={"GET"})
     */
    public function sectorsGetCollection(SectorRepository $sectorRepository): JsonResponse
    {
        
        $sectorsList = $sectorRepository->findAll();

        return $this->json([
            'sectors' => $sectorsList,
        ],
        Response::HTTP_OK,
        [],
        ['groups' => 'sectors_get_item']
        );
    }

    /**
     * Method to have a sector whose {id} is given
     * 
     * @Route("/sectors/{id}", name="sector_get_details", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function sectorsGetProfil(Sector $sector = null)
    {
        // 404 ?
        if ($sector === null) {
            // Returns an error if the sector is unknown
            return $this->json(['error' => 'Secteur d\'activité non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($sector, Response::HTTP_OK, [], ['groups' => 'sectors_get_item']);
    }

    /**
     * Method to add a sector
     * 
     * @Route("/sectors", name="sectors_add", methods={"POST"})
     */
    public function sectorsAdd(
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ) {
        // We need to retrieve the JSON content from the Request
        $jsonContent = $request->getContent();

        // Deserialize the JSON content into a Sector entity
        $sector = $serializer->deserialize($jsonContent, Sector::class, 'json');

        // Validation of the entity
        // @link https://symfony.com/doc/current/validation.html#using-the-validator-service
        $errors = $validator->validate($sector);

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
        $em->persist($sector);
        $em->flush();

        // We return a response that contains (REST !)
        return $this->json(
            // sector added
            $sector,
            // status code : 201 CREATED
            Response::HTTP_CREATED,
            // REST require location header + the URL of the created resource
            [
                'Location' => $this->generateUrl('api_v1_sector_get_details', ['id' => $sector->getId()])
            ],
            ['groups' => 'sectors_get_item']
        );
    }

    /**
     * Method to update a sector whose {id} is given
     * 
     * @Route("/sectors/{id}", name="sectors_update", methods={"PUT"}, requirements={"id"="\d+"})
     */
    public function sectorsUpdate(
        Sector $sector = null,
        SectorRepository $sectorRepository,
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ) {
        
        // 404 ?
        if ($sectorRepository === null) {
            // Returns an error if the sector is unknown
            return $this->json(['error' => 'Secteur d\'activité non trouvé.'], Response::HTTP_NOT_FOUND);
        }
        
        // We need to retrieve the JSON content from the Request
        $jsonContent = $request->getContent();

        // Deserialize the JSON content into a Sector entity
        $sectorReceived = $serializer->deserialize($jsonContent, Sector::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $sector]);

        // Validation of the entity
        // @link https://symfony.com/doc/current/validation.html#using-the-validator-service
        $errors = $validator->validate($sectorReceived);

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
            // sector updated
            $sector,
            // status code : 201 CREATED
            Response::HTTP_OK,
            // REST require locatiion header + the URL of the created resource
            [
                'Location' => $this->generateUrl('api_v1_sector_get_details', ['id' => $sector->getId()])
            ],
            ['groups' => 'sectors_get_item']
        );
    }

    /**
     * Method to remove a sector whose {id} is given
     * 
     * @Route("/sectors/{id}", name="sector_delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function sectorsDelete(Sector $sector = null, ManagerRegistry $doctrine)
    {
        // 404 ?
        if ($sector === null) {
            return $this->json(['error' => 'Secteur d\'activité non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $em = $doctrine->getManager();
        $em->remove($sector);
        $em->flush();

        return $this->json($sector, Response::HTTP_OK, [], ['groups' => 'sectors_get_item']);
    }
}

