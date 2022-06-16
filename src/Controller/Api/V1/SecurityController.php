<?php

namespace App\Controller\Api\V1;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class that manages resources of type User
 * 
 * @Route("/api/v1", name="api_")
 */
class SecurityController extends AbstractController
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("login", name="api_v1_login", methods={"POST"})
     * @return JsonResponse
     */
    public function apiLoginCheck(): JsonResponse
    {
        $user = $this->getUser();

        return new JsonResponse([
            'email' => $user->getUserIdentifier(),
            'roles' => $user->getRoles(),
        ]);
    }


    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout()
    {
        // controller can be blank: it will never be executed!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    /**
     * @Route("/getuser", name="app_getuser", methods={"GET"})
     */
    public function getUser()
    {        
        
        $user = $this->security->getUser();
        // dd($user->getId());

        // return new JsonResponse([
        //     $user->getId()
        // ]);

        return $this->json($user, Response::HTTP_OK, [], ['groups' => 'users_get_item']);

    }
}