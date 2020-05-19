# ayscom test

## Dependencies
 - Symfony 5.0
 - PHP ^7.2.5
 - Package symfony/http-client 5.0.*
 - Composer

## Setup
In order to set up this project follow the next instructions.
 
```
# clone the project to download its content or unzip the package
cd projects/
git clone https://github.com/baronnath/ayscom.git

# browse into the project folder and make composer install all dependencies
cd my-project/
composer install
```
  [Symfony documentation](https://symfony.com/doc/current/setup.html#setting-up-an-existing-symfony-project)

## Run the command
> $ php bin/console <website-url> <iterations>
- The website URL must include the http or https protocol.
- The iteration must be an integer larger than 0.
