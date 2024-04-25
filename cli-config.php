<?php
// cli-config.php
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

// Autoload files using Composer autoload
require_once __DIR__ . '/vendor/autoload.php';

// Load the environment variables from the .env file
$dotenv = new Symfony\Component\Dotenv\Dotenv();
$dotenv->load(__DIR__.'/.env');

// Retrieve the entity manager from your Symfony configuration
$entityManager = require __DIR__.'/doctrine_bootstrap.php';

return ConsoleRunner::createHelperSet($entityManager);
