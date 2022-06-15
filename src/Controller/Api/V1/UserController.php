<?php

namespace App\Controller\Api\V1;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class that manages resources of type User
 * 
 * @Route("/api/v1", name="api_v1_")
 */
class UserController extends AbstractController
{
    /**
     * Method to have all users
     * 
     * @Route("/users", name="users", methods={"GET"})
     */
    public function usersGetCollection(UserRepository $userRepository): JsonResponse
    {
        
        $usersList = $userRepository->findAll();

        return $this->json([
            'users' => $usersList,
        ],
        Response::HTTP_OK,
        [],
        ['groups' => 'users_get_collection']
        );
    }

    /**
     * Method to have a user whose {id} is given
     * 
     * @Route("/users/{id}/profil", name="user_get_profil", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function usersGetProfil(User $user = null)
    {
        // 404 ?
        if ($user === null) {
            // Returns an error if the user is unknown
            return $this->json(['error' => 'Utilisateur non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($user, Response::HTTP_OK, [], ['groups' => 'users_get_item']);
    }


    /**
     * Method to add a user
     * 
     * @Route("/subscribe", name="users_add", methods={"POST"})
     */
    public function usersAdd(
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $passwordHasher
    ) {
        // We need to retrieve the JSON content from the Request
        $jsonContent = $request->getContent();

        // Deserialize the JSON content into a User entity
        $user = $serializer->deserialize($jsonContent, User::class, 'json');

        // Validation of the entity
        // @link https://symfony.com/doc/current/validation.html#using-the-validator-service
        $errors = $validator->validate($user);

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

        // Hash user password
        $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());     
        $user->setPassword($hashedPassword);
    
        // backup in database
        $em = $doctrine->getManager();
        $em->persist($user);
        $em->flush();

        // We return a response that contains (REST !)
        return $this->json(
            // user added
            $user,
            // status code : 201 CREATED
            Response::HTTP_CREATED,
            // REST require locatiion header+ the URL of the created resource
            [
                'Location' => $this->generateUrl('api_v1_user_get_profil', ['id' => $user->getId()])
            ],
        );
    }

    /**
     * Method to update a user whose {id} is given
     * 
     * @Route("/users/{id}", name="users_update", methods={"PUT"}, requirements={"id"="\d+"})
     */
    public function usersUpdate(
        User $user = null,
        UserRepository $userRepository,
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ) {
        
        // 404 ?
        if ($userRepository === null) {
            // Returns an error if the user is unknown
            return $this->json(['error' => 'Utilisateur non trouvé.'], Response::HTTP_NOT_FOUND);
        }
        
        // We need to retrieve the JSON content from the Request
        $jsonContent = $request->getContent();

        // Deserialize the JSON content into a User entity
        $userReceived = $serializer->deserialize($jsonContent, User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);

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
        // $user->setEmail($userReceived->getEmail());
        // $user->setRoles($userReceived->getRoles());
        // $user->setPassword($userReceived->getPassword());
        $em->flush();

        // We return a response that contains (REST !)
        
        return $this->json(
            // user updated
            $user,
            // status code : 201 CREATED
            Response::HTTP_OK,
            // REST require locatiion header+ the URL of the created resource
            [
                'Location' => $this->generateUrl('api_v1_user_get_profil', ['id' => $user->getId()])
            ],
            ['groups' => 'users_get_item']
        );
    }

    /**
     * Method to remove a user whose {id} is given
     * 
     * @Route("/users/{id}", name="user_delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function usersDelete(User $user = null, ManagerRegistry $doctrine)
    {
        // 404 ?
        if ($user === null) {
            return $this->json(['error' => 'Utilisateur non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $em = $doctrine->getManager();
        $em->remove($user);
        $em->flush();

        return $this->json($user, Response::HTTP_OK, [], []);
    }
   
}
