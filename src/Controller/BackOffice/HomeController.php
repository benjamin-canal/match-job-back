<?php

namespace App\Controller\BackOffice;

use App\Repository\ContractRepository;
use App\Repository\ExperienceRepository;
use App\Repository\JobtitleRepository;
use App\Repository\SalaryRepository;
use App\Repository\SectorRepository;
use App\Repository\TechnologyRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="back_home", methods={"GET"})
     */
    public function home(
      UserRepository $userRepository,
      ContractRepository $contractRepository,
      ExperienceRepository $experienceRepository,
      JobtitleRepository $jobtitleRepository,
      SalaryRepository $salaryRepository,
      SectorRepository $sectorRepository,
      TechnologyRepository $technologyRepository
    ): Response
    {
      $usersNumber = count($userRepository->findAll());
      $contractsNumber = count($contractRepository->findAll());
      $experiencesNumber = count($experienceRepository->findAll());
      $jobtitlesNumber = count($jobtitleRepository->findAll());
      $salariesNumber = count($salaryRepository->findAll());
      $sectorsNumber = count($sectorRepository->findAll());
      $technologiesNumber = count($technologyRepository->findAll());
      
      return $this->render('home.html.twig', [
          'usersNumber' => $usersNumber,
          'contractsNumber' => $contractsNumber,
          'experiencesNumber' => $experiencesNumber,
          'jobtitlesNumber' => $jobtitlesNumber,
          'salariesNumber' => $salariesNumber,
          'sectorsNumber' => $sectorsNumber,
          'technologiesNumber' => $technologiesNumber,
      ]);
    }
}