<?php

namespace App\DataFixtures\Provider;

class ProviderMatchJob
{

  // technology
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

    // textColor  
    private $textColors = [
        '#323230',
        '#ffffff',
        '#000000',
        '#003444',
        '#00748f',
        '#ffce3b',
        '#2cbdaf',
        '#61dafb',
        '#35495e',
        '#666861',  
        '#ffce3b',
        '#2cbdaf',
        '#35495e',
        '#666861',
    ];

    // backgroudColors  
    private $backgroundColors = [
    '#f0db4d',
    '#e54c26',
    '#264de5',
    '#767ab3',
    '#ffffff',
    '#3670a2',
    '#649bd3',
    '#390092',
    '#2cbdaf',
    '#e83623',
    '#000000',
    '#41b883',
    '#000000',
    '#8bc500',
    '#d91404',
    '#ff2c1f',
    ];

  // sector  
  private $sectors = [
      'Dev web',
      'Agence',
      'Remote',
  ];

  // contract
  private $contracts = [
      'CDI',
      'CDD',
      'Intérim',
      'Freelance',
      'Stage',
      'Mission',
      'Alternance',
  ];

  // salary
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

  // jobTitle
  private $jobTitles = [
      'Développeur web Fullstack',
      'Développeur web Frontend',
      'Développeur web Backend',
  ];

  // experience
  private $experiences = [
      'inférieur à 1 ',
      'de 1 à 2',
      'de 2 à 5',
      'de 5 à 10',
      'plus de 10 ',
      'plus de 15',
      'supérieur à 20 ',
  ];


  /**
   * Return a random technology
   */
  public function technologyType()
  {
      return $this->technologies[array_rand($this->technologies)];
  }

  /**
   * Return a random sector
   */
  public function sectorType()
  {
      return $this->sectors[array_rand($this->sectors)];
  }

  /**
   * Return a random contract
   */
  public function contractType()
  {
      return $this->contracts[array_rand($this->contracts)];
  }

  /**
   * Return a random salary
   */
  public function salaryType()
  {
      return $this->salaries[array_rand($this->salaries)];
  }

  /**
   * Return a random jobTitle
   */
  public function jobTitleType()
  {
      return $this->jobTitles[array_rand($this->jobTitles)];
  }

  /**
   * Return a random experience
   */
  public function experienceType()
  {
      return $this->experiences[array_rand($this->experiences)];
  }

  /**
   * Return a random textColor
   */
  public function textColorType()
  {
      return $this->textColors[array_rand($this->textColors)];
  }

  /**
   * Return a random experience
   */
  public function backgroundColorType()
  {
      return $this->backgroundColors[array_rand($this->backgroundColors)];
  }
  
}
