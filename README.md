# Match Job

## Table of Contents

## About

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

* PHP 7.4.3+
* composer

### Installing

#### Cloning project

#### Setting up dependencies

```php
composer install
```

#### Setting Database

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

#### Install database

```php
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```
