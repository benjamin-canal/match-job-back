<?php

namespace App\Controller\Api\V1;

use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

        return $this->json($user, Response::HTTP_OK, [], []);
    }

    /**
     * Method to add a user
     * 
     * @Route("/users", name="users_add", methods={"POST"})
     */
    public function usersAdd(
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator,
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

                // On récupère les infos
                $property = $error->getPropertyPath();
                $message = $error->getMessage();

                // On ajoute le message dans un tableau à la clé $property
                // PHP gère lui-même l'existence du second tableau
                $cleanErrors[$property][] = $message;
            }

            return $this->json($cleanErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }


        // On la sauvegarde en base
        $em = $doctrine->getManager();
        $user->setCreatedAt(new DateTime());
        $em->persist($user);
        $em->flush();

        // On retourne une réponse qui contient (REST !)
        return $this->json(
            // L'utilisateur ajouté
            $user,
            // Le status code : 201 CREATED
            Response::HTTP_CREATED,
            // REST demande un header Location + l'URL de la ressource créée
            [
                'Location' => $this->generateUrl('api_v1_user_get_profil', ['id' => $user->getId()])
            ],
        );
    }

    /**
     * Method to update a user whose {id} is given
     * 
     * @Route("/users/{id}", name="users_update", methods={"PUT"})
     */
    public function usersUpdate(
        User $user = null,
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ) {
        // On doit récupérer le contenu JSON qui se trouve dans la Request
        $jsonContent = $request->getContent();

        // @link https://symfony.com/doc/current/components/serializer.html#deserializing-an-object
        // On "désérialise" le contenu JSON en entité de type Movie
        $userRequest = $serializer->deserialize($jsonContent, User::class, 'json');
        
        // On valide l'entité
        // @link https://symfony.com/doc/current/validation.html#using-the-validator-service
        $errors = $validator->validate($user);

        if (count($errors) > 0) {

            $cleanErrors = [];

            /** @var ConstraintViolation $error */
            foreach ($errors as $error) {

                // On récupère les infos
                $property = $error->getPropertyPath(); // 'title'
                $message = $error->getMessage(); // 'This value is already used.'

                $cleanErrors[$property][] = $message;
            }

            return $this->json($cleanErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // On la sauvegarde en base
        $em = $doctrine->getManager();
        $user->setEmail($userRequest->getEmail());
        $user->setRole($userRequest->getRole());
        $user->setPassword($userRequest->getPassword());
        $user->setUpdatedAt(new DateTime());
        $em->flush();

        // On retourne une réponse qui contient (REST !)
        
        return $this->json(
            // L'utilisateur modifié'
            $user,
            // Le status code : 201 CREATED
            Response::HTTP_OK,
            // REST demande un header Location + l'URL de la ressource créée
            [
                'Location' => $this->generateUrl('api_v1_user_get_profil', ['id' => $user->getId()])
            ],
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
