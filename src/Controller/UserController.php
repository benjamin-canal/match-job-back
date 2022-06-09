<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="app_user")
     */
    public function index(UserRepository $userRepository): JsonResponse
    {
        
        $usersList = $userRepository->findAll();
        return $this->json([
            'users' => $usersList,
        ],
        Response::HTTP_OK,
    );
    }
}
