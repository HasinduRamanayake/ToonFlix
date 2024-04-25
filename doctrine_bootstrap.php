<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once __DIR__ . '/vendor/autoload.php';

// Load environment variables
$dotenv = new Symfony\Component\Dotenv\Dotenv();
$dotenv->load(__DIR__.'/.env');

// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
$paths = [__DIR__."/application/models/Entity"];
$dbParams = array(
    'driver'   => 'pdo_mysql',
    'user'     => 'root',
    'password' => getenv('DB_PASSWORD'),
    'dbname'   => 'toonflix',
    'host'     => 'localhost'
);

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);
$entityManager = EntityManager::create($dbParams, $config);

return $entityManager;
