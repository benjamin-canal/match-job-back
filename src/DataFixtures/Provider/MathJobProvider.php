<?php

namespace App\DataFixtures\Provider;

class MatchJobProvider
{
   
    // 23 technology
    private $technologies = [
        'PHP',
        'C#',
        'JavaScript',
        'HTML5',
        'CSS3',
        'API',
        'Ruby',
        'Python',
        'Go',
        'Wordpress',
        'React JS',
        'Symfony',
        'Angular',
        'Sass',
        'Node JS',
        'Vue JS',
        'GitHub',
        'Laravel',
        'Visual Studio Code',
        'TypeScript',
        'Npm',
        'Yarn',
        'Bootstrap',
        'Stack Overflow',
    ];

    private $sectors = [
        'Dev web',
        'Agence',
        'Remote',
    ];

    private $contracts = [
        'CDI',
        'CDD',
        'Intérim',
        'Freelance',
        'Stage',
        'Mission',
        'Alternance',
    ];

    private $salaries = [
        'Selon expérience',
        '19 237 - 22 000',
        '22 000 - 24 000',
        '24 000 - 26 000',
        '26 000 - 28 000',
        '28 000 - 30 000',
        '30 000 - 32 000',
        '32 000 - 34 000',
        '34 000 - 36 000',
        '36 000 - 38 000',
        '38 000 - 40 000',
        '40 000 - 42 000',
        '42 000 - 44 000',
        '44 000 - 48 000',
        '48 000 - 50 000',
        '50 000 - 52 000',
        '52 000 - 54 000',
        '54 000 - 56 000',
        '56 000 - 58 000',
        '58 000 - 60 000',
        '60 000 - 62 000',
        'plus de 62 000',
    ];

    private $jobTitles = [
        'Développeur web Fullstack',
        'Développeur web Frontend',
        'Développeur web Backend',
    ];

    private $experiences = [
        'inférieur à 1 ',
        '1',
        '2',
        '3',
        '4',
        '5',
        '6',
        '7',
        '8',
        '9',
        '10',
        '11',
        '12',
        '13',
        '14',
        '15',
        '16',
        '17',
        '18',
        '19',
        '20',
        'supérieur à 20 ',
    ];


    /**
     * Retourne une technology au hasard
     */
    public function technologyType()
    {
        return $this->technologies[array_rand($this->technologies)];
    }

    /**
     * Retourne un sector au hasard
     */
    public function sectorType()
    {
        return $this->sectors[array_rand($this->sectors)];
    }

    /**
     * Retourne un sector au hasard
     */
    public function contractType()
    {
        return $this->contracts[array_rand($this->contracts)];
    }

    /**
     * Retourne un salary au hasard
     */
    public function salaryType()
    {
        return $this->salaries[array_rand($this->salaries)];
    }

    /**
     * Retourne un jobTitle au hasard
     */
    public function jobTitleType()
    {
        return $this->jobTitles[array_rand($this->jobTitles)];
    }

    /**
     * Retourne un jobTitle au hasard
     */
    public function experienceType()
    {
        return $this->experiences[array_rand($this->experiences)];
    }

}
