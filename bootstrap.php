<?php

require __DIR__ . '/vendor/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$isDevMode = false;

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

$paths = [
    "src/Entities"
];


$dbParams = array(
    'driver'   => getenv('DB_DRIVE'),
    'user'     => getenv('DB_USER'),
    'password' => getenv('DB_PASSWD'),
    'dbname'   => getenv('DB_NAME'),
);

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);