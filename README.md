# Match Job

> "Trouvez le Job ou le Candidat de vos rêves !!!"

## Table of Contents

1. [About](#about)
2. [Getting Started](#getting-started)
3. [Installing](#installing)

## About

The MatchJob-Back project manages the backend of the MatchJob-Front project <https://github.com/O-clock-Boson/projet-match-job-front>

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

* PHP 7.4.3+
* composer

### Technologies

* Symfony 5.4
* Bootstrap 5.0.2

## Installing

### Cloning project

* Navigate to the directory that you would like to clone the repository
* Clone the remote repository and create a local copy on your machine using this command:
  
```cmd
git clone https://github.com/O-clock-Boson/projet-match-job-back.git
```

### Setting up dependencies

```php
composer install
```

### Setting Database

* Create `.env.local` file for the first time at the root of the project.
  
```php
nano .env.local
```

* Add the following line for the database

```php
nano .env.local
```

* Adapt the elements between brackets according to the connection information to your database (e.g. [*Username*] by explorateur)

```php
DATABASE_URL="mysql://[*Username*]:[*password*]@127.0.0.1:3306/matchjob?serverVersion=mariadb-10.3.25"
```

### Install database

```php
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```
