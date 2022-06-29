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
    public function salariesGetCollection(SalaryRepository $salaryRepository): JsonResponse
    {
        
        $salariesList = $salaryRepository->findAll();

        return $this->json([
            'salaries' => $salariesList,
        ],
        Response::HTTP_OK,
        [],
        ['groups' => 'salaries_get_item']
        );
    }

    /**
     * Method to have a salary whose {id} is given
     * 
     * @Route("/salaries/{id}", name="salary_get_details", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function salariesGetProfil(Salary $salary = null)
    {
        // 404 ?
        if ($salary === null) {
            // Returns an error if the salarie is unknown
            return $this->json(['error' => 'Tranche de salaire souhaitée : non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($salary, Response::HTTP_OK, [], ['groups' => 'salaries_get_item']);
    }

    /**
     * Method to add a salary
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
        $salary = $serializer->deserialize($jsonContent, Salary::class, 'json');

        // Validation of the entity
        // @link https://symfony.com/doc/current/validation.html#using-the-validator-service
        $errors = $validator->validate($salary);

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
        $em->persist($salary);
        $em->flush();

        // We return a response that contains (REST !)
        return $this->json(
            // salarie added
            $salary,
            // status code : 201 CREATED
            Response::HTTP_CREATED,
            // REST require location header + the URL of the created resource
            [
                'Location' => $this->generateUrl('api_v1_salarie_get_details', ['id' => $salary->getId()])
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
        Salary $salary = null,
        SalaryRepository $salaryRepository,
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ) {
        
        // 404 ?
        if ($salaryRepository === null) {
            // Returns an error if the salarie is unknown
            return $this->json(['error' => 'Tranche de salaire souhaitée : non trouvée.'], Response::HTTP_NOT_FOUND);
        }
        
        // We need to retrieve the JSON content from the Request
        $jsonContent = $request->getContent();

        // Deserialize the JSON content into a Salary entity
        $salaryReceived = $serializer->deserialize($jsonContent, Salary::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $salary]);

        // Validation of the entity
        // @link https://symfony.com/doc/current/validation.html#using-the-validator-service
        $errors = $validator->validate($salaryReceived);

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
            $salary,
            // status code : 201 CREATED
            Response::HTTP_OK,
            // REST require location header + the URL of the created resource
            [
                'Location' => $this->generateUrl('api_v1_salarie_get_details', ['id' => $salary->getId()])
            ],
            ['groups' => 'salaries_get_item']
        );
    }

    /**
     * Method to remove a salarie whose {id} is given
     * 
     * @Route("/salaries/{id}", name="salarie_delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function salariesDelete(Salary $salary = null, ManagerRegistry $doctrine)
    {
        // 404 ?
        if ($salary === null) {
            return $this->json(['error' => 'Tranche de salaire souhaitée : non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        $em = $doctrine->getManager();
        $em->remove($salary);
        $em->flush();

        return $this->json($salary, Response::HTTP_OK, [], ['groups' => 'salaries_get_item']);
    }
}

