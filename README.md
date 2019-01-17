Requirements
------------

  * PHP 7.1.3 or higher;
  * PDO-SQLite PHP extension enabled;
  * and the [usual Symfony application requirements][1].


Install project first time
========================

Download files with Git

```bash
git clone git@github.com:maherkheder3/symfony-vue-cms.git
cd symfony-vue-cms

# install all libleres
composer install

# create database if you donot have one ( see .env file Important to configuration)
php bin/console doctrine:database:create

# crate Migrations (must the databse is Empty)
php bin/console doctrine:migrations:migrate

# to import the data from AppFixtures "admin password in this file"
php bin/console doctrine:fixture:load   
```


Usage
-----

There's no need to configure anything to run the application. Just execute this
command to run the built-in web server and access the application in your
browser at <http://localhost:8000>:

```bash
$ php bin/console server:run
```

Tests
-----

Execute this command to run tests:

```bash
$ ./vendor/bin/simple-phpunit
```

Backend
========================
every codes to need in Backend

To test the Controllers : [contollers list](http://127.0.0.1:8001/en/login)


```bash
# create Controller
bin/console make:crud Course

# crate Migrations (must the databse is Empty)
php bin/console doctrine:migrations:migrate

# import the data from AppFixtures
php bin/console doctrine:fixture:load  
```

FrontEnd
========================
every codes to need in FrontEnd

```bash
# rebild styles files
yarn run encore dev
yarn run encore dev --watch
```

More Options
========================
Every options to need to development in Backed & Froned 

```bash
# Important , clear cache ...
bin/console cache:clear

```


Create a table
======================

```bash

```