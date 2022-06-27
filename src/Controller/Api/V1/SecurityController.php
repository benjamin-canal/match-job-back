<?php

namespace App\Controller\Api\V1;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class that manages the security of the application
 * 
 * @Route("/api/v1", name="api_v1_")
 */
class SecurityController extends AbstractController
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("login", name="login", methods={"POST"})
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
     * @Route("/logout", name="logout", methods={"GET"})
     */
    public function logout()
    {
        // controller can be blank: it will never be executed!
    }

    /**
     * @Route("/getuser", name="getuser", methods={"GET"})
     */
    public function getUser()
    {         
        $user = $this->security->getUser();
        
        return $this->json($user, Response::HTTP_OK, [], ['groups' => 'users_get_item']);
    }
}