<?php

namespace App\Controller\Api\V1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class that manages resources of type User
 * 
 * @Route("/api/v1", name="api_")
 */
class SecurityController extends AbstractController
{

    /**
     * @Route("/login", name="api_v1_login", methods={"GET", "POST"})
     * @return JsonResponse
     */
    public function apiLoginCheck(): JsonResponse
    {
        // $user = $this->getUser();

        // dd($user);

        return new JsonResponse([
            // 'email' => $user->getUserIdentifier(),
            // 'roles' => $user->getRoles(),
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
}