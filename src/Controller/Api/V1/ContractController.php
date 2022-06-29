<?php

namespace App\Controller\Api\V1;

use App\Entity\Contract;
use App\Repository\ContractRepository;
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
 * Class that manages ressources of type Contract
 * 
 * @Route("/api/v1", name="api_v1_")
 */
class ContractController extends AbstractController
{
    /**
     * Method to have all contracts
     * 
     * @Route("/contracts", name="contracts", methods={"GET"})
     */
    public function contractsGetCollection(ContractRepository $contractRepository): JsonResponse
    {
        
        $contractsList = $contractRepository->findAll();

        return $this->json([
            'contracts' => $contractsList,
        ],
        Response::HTTP_OK,
        [],
        ['groups' => 'contracts_get_item']
        );
    }

    /**
     * Method to have a contract whose {id} is given
     * 
     * @Route("/contracts/{id}", name="contract_get_details", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function contractsGetProfil(Contract $contract = null)
    {
        // 404 ?
        if ($contract === null) {
            // Returns an error if the contract is unknown
            return $this->json(['error' => 'Contrat non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($contract, Response::HTTP_OK, [], ['groups' => 'contracts_get_item']);
    }

    /**
     * Method to add a contract
     * 
     * @Route("/contracts", name="contracts_add", methods={"POST"})
     */
    public function contractsAdd(
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ) {
        // We need to retrieve the JSON content from the Request
        $jsonContent = $request->getContent();

        // Deserialize the JSON content into a Contract entity
        $contract = $serializer->deserialize($jsonContent, Contract::class, 'json');

        // Validation of the entity
        // @link https://symfony.com/doc/current/validation.html#using-the-validator-service
        $errors = $validator->validate($contract);

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
        $em->persist($contract);
        $em->flush();

        // We return a response that contains (REST !)
        return $this->json(
            // contract added
            $contract,
            // status code : 201 CREATED
            Response::HTTP_CREATED,
            // REST require location header+ the URL of the created resource
            [
                'Location' => $this->generateUrl('api_v1_contract_get_details', ['id' => $contract->getId()])
            ],
            ['groups' => 'contracts_get_item']
        );
    }

    /**
     * Method to update a contract whose {id} is given
     * 
     * @Route("/contracts/{id}", name="contracts_update", methods={"PUT"}, requirements={"id"="\d+"})
     */
    public function contractsUpdate(
        Contract $contract = null,
        ContractRepository $contractRepository,
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ) {
        
        // 404 ?
        if ($contractRepository === null) {
            // Returns an error if the contract is unknown
            return $this->json(['error' => 'Contrat non trouvé.'], Response::HTTP_NOT_FOUND);
        }
        
        // We need to retrieve the JSON content from the Request
        $jsonContent = $request->getContent();

        // Deserialize the JSON content into a Contract entity
        $contractReceived = $serializer->deserialize($jsonContent, Contract::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $contract]);

        // Validation of the entity
        // @link https://symfony.com/doc/current/validation.html#using-the-validator-service
        $errors = $validator->validate($contractReceived);

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
            // contract updated
            $contract,
            // status code : 201 CREATED
            Response::HTTP_OK,
            // REST require location header+ the URL of the created resource
            [
                'Location' => $this->generateUrl('api_v1_contract_get_details', ['id' => $contract->getId()])
            ],
            ['groups' => 'contracts_get_item']
        );
    }

    /**
     * Method to remove a contract whose {id} is given
     * 
     * @Route("/contracts/{id}", name="contract_delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function contractsDelete(Contract $contract = null, ManagerRegistry $doctrine)
    {
        // 404 ?
        if ($contract === null) {
            return $this->json(['error' => 'Contrat non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $em = $doctrine->getManager();
        $em->remove($contract);
        $em->flush();

        return $this->json($contract, Response::HTTP_OK, [], ['groups' => 'contracts_get_item']);
    }
}

