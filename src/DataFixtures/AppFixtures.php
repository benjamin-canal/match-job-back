<?php

namespace App\DataFixtures;

use Faker;
use DateTime;
use App\Entity\User;
use App\Entity\Adress;
use App\Entity\Salary;
use App\Entity\Sector;
use App\Entity\Company;
use App\Entity\Contract;
use App\Entity\Jobtitle;
use App\Entity\Candidate;
use App\Entity\Recruiter;
use App\Entity\Experience;
use App\Entity\Technology;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Provider\MatchJobProvider;


class AppFixtures extends Fixture
{   
    /**
     * Les propriétés qui vont accueillir les services nécessaires à la classe de Fixtures
     */
    private $connection;

    
    /**
     * On récupère les services utiles via le constructeur
     */
    public function __construct(Connection $connection)
    {
        // On récupère la connexion à la BDD (DBAL ~= PDO)
        // pour exécuter des requêtes manuelles en SQL pur
        $this->connection = $connection;
    }


    /**
     * Permet de TRUNCATE les tables et de remettre les AI à 1
     */
    private function truncate()
    {
        // Désactivation la vérification des contraintes FK
        $this->connection->executeQuery('SET foreign_key_checks = 0');
        // On tronque
        $this->connection->executeQuery('TRUNCATE TABLE adress');
        $this->connection->executeQuery('TRUNCATE TABLE candidate');
        $this->connection->executeQuery('TRUNCATE TABLE company');
        $this->connection->executeQuery('TRUNCATE TABLE contract');
        $this->connection->executeQuery('TRUNCATE TABLE experience');
        $this->connection->executeQuery('TRUNCATE TABLE jobtitle');
        $this->connection->executeQuery('TRUNCATE TABLE recruiter');
        $this->connection->executeQuery('TRUNCATE TABLE salary');
        $this->connection->executeQuery('TRUNCATE TABLE sector');
        $this->connection->executeQuery('TRUNCATE TABLE technology');
        $this->connection->executeQuery('TRUNCATE TABLE user');
        
    }


    public function load(ObjectManager $manager): void
    {   
        // On TRUNCATE manuellement
        $this->truncate();

        // @link https://fakerphp.github.io/
        // use the factory to create a Faker\Generator instance
        $faker = Faker\Factory::create('fr_FR');

        // On peut fixer le "seed" du générateur (pour avoir toujours les mêmes données dans la BDD)
        $faker->seed(2022);

        // On instancie notre provider custom MatchJob
        $matchJobProvider = new MatchJobProvider();
        // On ajoute MatchJobProvider à faker
        $faker->addProvider($matchJobProvider);


        // Adress
        $addressList = [];

        for ($a = 1; $a <= 20; $a++) {
        $adress = new Adress();
        $adress->setStreetNumber($faker->numberBetween($min = 1, $max = 200));
        $adress->setStreetName($faker->unique()->streetName());
        $adress->setCity($faker->unique()->city());
        $adress->setZip($faker->unique()->numberBetween($min = 1, $max = 32700));
        
        // On l'ajoute à la liste pour usage ultérieur
        // Patch pour éviter les doublons
        $addressList[] = $adress;
            // On persiste
            $manager->persist($adress);
        }


        // Sector
        $sectorList =  [];

        for ($s = 1; $s <= 3 ; $s++) {
            $sector = new Sector();
            $sector->setSectorName($faker->sectorType());
            $sector->setCreatedAt(new DateTime);
        
        $sectorList[] = $sector;

            $manager->persist($sector);
        }


        // Company
        $companyList = [];

        for ($c = 1; $c <= 20; $c++) {
            $company = new Company();
            $company->setAdress($adress);
            $company->setCompanyName($faker->unique()->company());
            $company->setCreatedAt(new DateTime());
            $company->setSector($sector);
        
            $companyList[] = $company;

            $manager->persist($company);
        }

        
        // Technology
        $technologyList = [];

        for ($t = 1; $t <= 23 ; $t++) {
            $technology = new Technology();
            $technology->setTechnologyName($faker->technologyType());
            $technology->setCreatedAt(new DateTime);
        
        $technologyList[] = $technology;

            $manager->persist($technology);
        }

        // Salary
        $salaryList = [];
        
        for ($s = 1; $s <= 22 ; $s++) {
            $salary = new Salary();
            $salary->setName($faker->salaryType());
            $salary->setCreatedAt(new DateTime);
        
        $salaryList[] = $salary;

            $manager->persist($salary);
        }


        // Experience
        $experienceList = [];
        
        for ($e = 1; $e <= 20 ; $e++) {
            $experience = new Experience();
            $experience->setYearsNumber($faker->unique()->numberBetween($min = 1, $max = 20));
            $experience->setCreatedAt(new DateTime);
        
        $experienceList[] = $experience;

            $manager->persist($experience);
        }


        // Contract
        $contractList = [];
        
        for ($c = 1; $c <= 7 ; $c++) {
            $contract = new Contract();
            $contract->setName($faker->contractType());
            $contract->setCreatedAt(new DateTime);
        
        $contractList[] = $contract;

            $manager->persist($contract);
        }


        // JobTitle
        $jobTitleList = [];
        
        for ($j = 1; $j <= 3 ; $j++) {
            $jobTitle = new Jobtitle();
            $jobTitle->setTitle($faker->jobTitleType());
            $jobTitle->setCreatedAt(new DateTime);
        
        $jobTitleList[] = $jobTitle;

            $manager->persist($jobTitle);
        }


        // User role admin
        $user = new User();
        $user->setEmail('admin@admin.com');
        $user->setRole('admin');
        $user->setPassword('admin');
        $user->setCreatedAt(new DateTime);

        $manager->persist($user);


        // Users role recruiter
        $userrecList = [];

        for ($u = 1; $u <= 5; $u++) {
            // Nouveau user role recruiter
            $recruiter = new User();
            $recruiter->setEmail($faker->safeEmail());
            $recruiter->setRole('recruiter');
            $recruiter->setPassword('recruiter');
            $recruiter->setCreatedAt(new DateTime);

            $userrecList[] = $recruiter;

            $manager->persist($recruiter);
        }


        // Users role candidate
        $usercandList = [];

        for ($u = 1; $u <= 5; $u++) {
            // Nouveau user role candidate
            $candidate = new User();
            $candidate->setEmail($faker->unique()->safeEmail());
            $candidate->setRole('candidate');
            $candidate->setPassword('candidate');
            $candidate->setCreatedAt(new DateTime);
           
            $usercandList[] = $candidate;

            $manager->persist($candidate);
        }

        // Candidate
      

        for ($c = 1; $c <= 5; $c++) {
            // Nouveau candidate
            
            $user = $usercandList[mt_rand(0, count($usercandList) -1)];
            $salary = $salaryList[mt_rand(0, count($salaryList) -1)];
            $adress = $addressList[mt_rand(0, count($addressList) -1)];
            $experience = $experienceList[mt_rand(0, count($experienceList) -1)];
            $contract = $contractList[mt_rand(0, count($contractList) -1)];
            $jobTitle = $jobTitleList[mt_rand(0, count($jobTitleList) -1)];
                
            $candidate = new Candidate();
            $candidate->setUser($user);
            $candidate->setCreatedAt(new DateTime);
            $candidate->setFirstname($faker->firstName());
            $candidate->setLastname($faker->lastName());
            $candidate->setGenre($faker->numberBetween($min = 1, $max = 2));
            $candidate->setPhoneNumber($faker->numberBetween($min = 10, $max = 2000));
            $candidate->setPicture('https://picsum.photos/id/' . $faker->numberBetween(1, 100) . '/100/100');
            $candidate->setPortfolio($faker->unique()->url());
            $candidate->setResume($faker->unique()->url());
            $candidate->setDescription($faker->text(100));
            $candidate->setExperience($experience);
            $candidate->setSalary($salary);
            $candidate->setAdress($adress);
            $candidate->setContract($contract);
            $candidate->setJobtitle($jobTitle);
            


            $manager->persist($candidate);
        }

        // Recruiter

        for ($r = 1; $r <= 5; $r++) {
            // Nouveau recruiter

            $user = $userrecList[mt_rand(0, count($userrecList) -1)];
            $company = $companyList[mt_rand(0, count($companyList) -1)];

            $recruiter = new Recruiter();
            $recruiter->setCreatedAt(new DateTime);
            $recruiter->setUser($user);
            $recruiter->setFirstname($faker->unique()->firstName());
            $recruiter->setLastname($faker->unique()->lastName());
            $recruiter->setPhoneNumber($faker->numberBetween($min = 10, $max = 2000));
            $recruiter->setCompany($company);

            $manager->persist($recruiter);
        }


        $manager->flush();
    }
}
