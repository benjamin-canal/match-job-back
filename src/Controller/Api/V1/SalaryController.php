<?php

namespace App\Controller\Api\V1;

use App\Entity\Salary;
use App\Repository\SalaryRepository;
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
 * Class that manages ressources of type Salary
 * 
 * @Route("/api/v1", name="api_v1_")
 */
class SalaryController extends AbstractController
{
    /**
     * Method to have all salaries
     * 
     * @Route("/salaries", name="salaries", methods={"GET"})
     */
    public function salariesGetCollection(SalaryRepository $salarieRepository): JsonResponse
    {
        
        $salariesList = $salarieRepository->findAll();

        return $this->json([
            'salaries' => $salariesList,
        ],
        Response::HTTP_OK,
        [],
        ['groups' => 'salaries_get_item']
        );
    }

    /**
     * Method to have a salarie whose {id} is given
     * 
     * @Route("/salaries/{id}", name="salarie_get_details", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function salariesGetProfil(Salary $salarie = null)
    {
        // 404 ?
        if ($salarie === null) {
            // Returns an error if the salarie is unknown
            return $this->json(['error' => 'Tranche de salaire souhaitée : non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($salarie, Response::HTTP_OK, [], ['groups' => 'salaries_get_item']);
    }

    /**
     * Method to add a salarie
     * 
     * @Route("/salaries", name="salaries_add", methods={"POST"})
     */
    public function salariesAdd(
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ) {
        // We need to retrieve the JSON content from the Request
        $jsonContent = $request->getContent();

        // Deserialize the JSON content into a Salary entity
        $salarie = $serializer->deserialize($jsonContent, Salary::class, 'json');

        // Validation of the entity
        // @link https://symfony.com/doc/current/validation.html#using-the-validator-service
        $errors = $validator->validate($salarie);

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
        $em->persist($salarie);
        $em->flush();

        // We return a response that contains (REST !)
        return $this->json(
            // salarie added
            $salarie,
            // status code : 201 CREATED
            Response::HTTP_CREATED,
            // REST require location header+ the URL of the created resource
            [
                'Location' => $this->generateUrl('api_v1_salarie_get_details', ['id' => $salarie->getId()])
            ],
            ['groups' => 'salaries_get_item']
        );
    }

    /**
     * Method to update a salarie whose {id} is given
     * 
     * @Route("/salaries/{id}", name="salaries_update", methods={"PUT"}, requirements={"id"="\d+"})
     */
    public function salariesUpdate(
        Salary $salarie = null,
        SalaryRepository $salarieRepository,
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ) {
        
        // 404 ?
        if ($salarieRepository === null) {
            // Returns an error if the salarie is unknown
            return $this->json(['error' => 'Tranche de salaire souhaitée : non trouvée.'], Response::HTTP_NOT_FOUND);
        }
        
        // We need to retrieve the JSON content from the Request
        $jsonContent = $request->getContent();

        // Deserialize the JSON content into a Salary entity
        $userReceived = $serializer->deserialize($jsonContent, Salary::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $salarie]);

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
            // salarie updated
            $salarie,
            // status code : 201 CREATED
            Response::HTTP_OK,
            // REST require location header+ the URL of the created resource
            [
                'Location' => $this->generateUrl('api_v1_salarie_get_details', ['id' => $salarie->getId()])
            ],
            ['groups' => 'salaries_get_item']
        );
    }

    /**
     * Method to remove a salarie whose {id} is given
     * 
     * @Route("/salaries/{id}", name="salarie_delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function salariesDelete(Salary $salarie = null, ManagerRegistry $doctrine)
    {
        // 404 ?
        if ($salarie === null) {
            return $this->json(['error' => 'Tranche de salaire souhaitée : non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        $em = $doctrine->getManager();
        $em->remove($salarie);
        $em->flush();

        return $this->json($salarie, Response::HTTP_OK, [], ['groups' => 'salaries_get_item']);
    }
}

