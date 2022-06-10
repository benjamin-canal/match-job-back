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
 * Classe qui s'occupe des ressources de type User
 * 
 * @Route("/api/v1", name="api_v1_")
 */
class UserController extends AbstractController
{
    /**
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
     * @Route("/users/{id}/profil", name="user_get_profil", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function usersGetProfil(User $user = null)
    {
        // 404 ?
        if ($user === null) {
            // Voir si meilleure solution ici : https://symfony.com/doc/current/controller/error_pages.html#overriding-error-output-for-non-html-formats
            return $this->json(['error' => 'Utilisateur non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($user, Response::HTTP_OK, [], []);
    }

    /**
     * @Route("/users", name="users_add", methods={"POST"})
     */
    public function usersAdd(
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ) {
        // On doit récupérer le contenu JSON qui se trouve dans la Request
        $jsonContent = $request->getContent();

        // Graĉe au Denormalizer ajouté dans src/Serializer
        // et en transmettant des ids sur les relations depius le JSON d'entrée
        // Par ex.
        // "genres": [
        //     1384,
        //     1402
        // ]
        // le denormalizer ira chercher les entités liées existantes dans la base
        // et fera le lien automatiquement avec $movie

        // @link https://symfony.com/doc/current/components/serializer.html#deserializing-an-object
        // On "désérialise" le contenu JSON en entité de type Movie
        $user = $serializer->deserialize($jsonContent, User::class, 'json');

        // On valide l'entité
        // @link https://symfony.com/doc/current/validation.html#using-the-validator-service
        $errors = $validator->validate($user);

        // 0 => Symfony\Component\Validator\ConstraintViolation {#979 ▼
        //     -message: "This value is already used."
        //     -messageTemplate: "This value is already used."
        //     -parameters: array:1 [▶]
        //     -plural: null
        //     -root: App\Entity\Movie {#891 ▶}
        //     -propertyPath: "title"
        //     -invalidValue: ""
        //     -constraint: Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity {#784 ▶}
        //     -code: "23bd9dbf-6b9b-41cd-a99e-4844bcf3077f"
        //     -cause: array:1 [▶]
        // }

        if (count($errors) > 0) {

            // On boucle sur le tableau d'erreurs
            // On crée un tableau pour retourner un JSON propre au client
            // Par ex.
            // $errors = [
            //     'title' => [
            //         'This value is already used.',
            //         'This value can not be blank.',
            //     ],
            //     'type' => [
            //         'This value can not be blank.',
            //     ]
            // ];

            $cleanErrors = [];

            /** @var ConstraintViolation $error */
            foreach ($errors as $error) {

                // On récupère les infos
                $property = $error->getPropertyPath(); // 'title'
                $message = $error->getMessage(); // 'This value is already used.'

                // On push tout ça dans un tableau à la clé $property

                // // On crée un tableau à la clé $property s'il n'existe pas déjà
                // if (!array_key_exists($property, $cleanErrors)) {
                //     $cleanErrors[$property] = [];
                // }
                // // On ajoute le message dedans
                // $cleanErrors[$property][] = $message;

                // OU plus court
                // On ajoute le message dans un tableau à la clé $property
                // PHP gère lui-même l'existence du second tableau
                $cleanErrors[$property][] = $message;
                // array_push($cleanErrors[$property], $message);
            }

            return $this->json($cleanErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
            // On peut aussi retourner $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY)
            // Si on ne souhaite pas reformater la sortie
        }

        // On la sauvegarde en base
        $em = $doctrine->getManager();
        $em->persist($user);
        $em->flush();

        // On retourne une réponse qui contient (REST !)
        // - un status code 201
        // - un en-tête (header) Location: URL_DE_LA_RESSOURCE_CREEE
        // - option perso : le JSON de l'entité créée
        return $this->json(
            // Le film créé
            $user,
            // Le status code : 201 CREATED
            // utilisons les constantes de classe !
            Response::HTTP_CREATED,
            // REST demande un header Location + l'URL de la ressource créée
            // (un tableau clé-valeur)
            [
                'Location' => $this->generateUrl('api_v1_user_get_profil', ['id' => $user->getId()])
            ],
        );
    }

    /**
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

            // On boucle sur le tableau d'erreurs
            // On crée un tableau pour retourner un JSON propre au client
            // Par ex.
            // $errors = [
            //     'title' => [
            //         'This value is already used.',
            //         'This value can not be blank.',
            //     ],
            //     'type' => [
            //         'This value can not be blank.',
            //     ]
            // ];

            $cleanErrors = [];

            /** @var ConstraintViolation $error */
            foreach ($errors as $error) {

                // On récupère les infos
                $property = $error->getPropertyPath(); // 'title'
                $message = $error->getMessage(); // 'This value is already used.'

                // On push tout ça dans un tableau à la clé $property

                // // On crée un tableau à la clé $property s'il n'existe pas déjà
                // if (!array_key_exists($property, $cleanErrors)) {
                //     $cleanErrors[$property] = [];
                // }
                // // On ajoute le message dedans
                // $cleanErrors[$property][] = $message;

                // OU plus court
                // On ajoute le message dans un tableau à la clé $property
                // PHP gère lui-même l'existence du second tableau
                $cleanErrors[$property][] = $message;
                // array_push($cleanErrors[$property], $message);
            }

            return $this->json($cleanErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // On la sauvegarde en base
        $em = $doctrine->getManager();
        $user->setEmail($userRequest->getEmail());
        $user->setRole($userRequest->getRole());
        $user->setPassword($userRequest->getPassword());
        $user->setUpdatedAt(new DateTime());
        // $em->persist($user);
        $em->flush();

        // On retourne une réponse qui contient (REST !)
        // - un status code 201
        // - un en-tête (header) Location: URL_DE_LA_RESSOURCE_CREEE
        // - option perso : le JSON de l'entité créée
        return $this->json(
            // L'utilisateur modifié'
            $user,
            // Le status code : 201 CREATED
            // utilisons les constantes de classe !
            Response::HTTP_OK,
            // REST demande un header Location + l'URL de la ressource créée
            // (un tableau clé-valeur)
            [
                'Location' => $this->generateUrl('api_v1_user_get_profil', ['id' => $user->getId()])
            ],
        );
    }

    /**
     * @Route("/users/{id}", name="user_delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function usersDelete(User $user = null, ManagerRegistry $doctrine)
    {
        // 404 ?
        if ($user === null) {
            // Voir si meilleure solution ici : https://symfony.com/doc/current/controller/error_pages.html#overriding-error-output-for-non-html-formats
            return $this->json(['error' => 'Utilisateur non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $em = $doctrine->getManager();
        $em->remove($user);
        $em->flush();

        return $this->json($user, Response::HTTP_OK, [], []);
    }

   
}
