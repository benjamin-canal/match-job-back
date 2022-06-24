<?php

namespace App\Controller\Api\V1;

use App\Entity\Company;
use App\Repository\CompanyRepository;
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
 * Class that manages ressources of type Company
 * 
 * @Route("/api/v1", name="api_v1_")
 */
class CompanyController extends AbstractController
{
    /**
     * Method to have all compagnies
     * 
     * @Route("/compagnies", name="compagnies", methods={"GET"})
     */
    public function compagniesGetCollection(CompanyRepository $companyRepository): JsonResponse
    {
        
        $compagniesList = $companyRepository->findAll();

        return $this->json([
            'compagnies' => $compagniesList,
        ],
        Response::HTTP_OK,
        [],
        ['groups' => ['compagnies_get_item', 'jobs_get_collection']]);
    }

    /**
     * Method to have a company whose {id} is given
     * 
     * @Route("/compagnies/{id}", name="company_get_details", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function compagniesGetProfil(Company $company = null)
    {
        // 404 ?
        if ($company === null) {
            // Returns an error if the company is unknown
            return $this->json(['error' => 'Entreprise non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($company, Response::HTTP_OK, [], ['groups' => ['compagnies_get_item', 'jobs_get_collection']]);
    }

    /**
     * Method to add a company
     * 
     * @Route("/compagnies", name="compagnies_add", methods={"POST"})
     */
    public function compagniesAdd(
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ) {
        // We need to retrieve the JSON content from the Request
        $jsonContent = $request->getContent();

        // Deserialize the JSON content into a Company entity
        $company = $serializer->deserialize($jsonContent, Company::class, 'json');

        // Validation of the entity
        // @link https://symfony.com/doc/current/validation.html#using-the-validator-service
        $errors = $validator->validate($company);

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
        $em->persist($company);
        $em->flush();

        // We return a response that contains (REST !)
        return $this->json(
            // company added
            $company,
            // status code : 201 CREATED
            Response::HTTP_CREATED,
            // REST require location header+ the URL of the created resource
            [
                'Location' => $this->generateUrl('api_v1_company_get_details', ['id' => $company->getId()])
            ],
            ['groups' => ['compagnies_get_item', 'jobs_get_collection']]
        );
    }

    /**
     * Method to update a company whose {id} is given
     * 
     * @Route("/compagnies/{id}", name="compagnies_update", methods={"PUT"}, requirements={"id"="\d+"})
     */
    public function compagniesUpdate(
        Company $company = null,
        CompanyRepository $companyRepository,
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ) {
        
        // 404 ?
        if ($companyRepository === null) {
            // Returns an error if the company is unknown
            return $this->json(['error' => 'Entreprise non trouvée.'], Response::HTTP_NOT_FOUND);
        }
        
        // We need to retrieve the JSON content from the Request
        $jsonContent = $request->getContent();

        // Deserialize the JSON content into a Company entity
        $userReceived = $serializer->deserialize($jsonContent, Company::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $company]);

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
            // company updated
            $company,
            // status code : 201 CREATED
            Response::HTTP_OK,
            // REST require location header+ the URL of the created resource
            [
                'Location' => $this->generateUrl('api_v1_company_get_details', ['id' => $company->getId()])
            ],
            ['groups' => ['compagnies_get_item', 'jobs_get_collection']]
        );
    }

    /**
     * Method to remove a company whose {id} is given
     * 
     * @Route("/compagnies/{id}", name="company_delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function compagniesDelete(Company $company = null, ManagerRegistry $doctrine)
    {
        // 404 ?
        if ($company === null) {
            return $this->json(['error' => 'Entreprise non trouvée.'], Response::HTTP_NOT_FOUND);
        }

        $em = $doctrine->getManager();
        $em->remove($company);
        $em->flush();

        return $this->json($company, Response::HTTP_OK, [], ['groups' => ['compagnies_get_item', 'jobs_get_collection']]);
    }
}

