<?php


/**
 * src/scripts/create_users.php
 */
require dirname(__DIR__, 2) . '/vendor/autoload.php';

use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;
use MiW\Results\Utility\Utils;

// Carga las variables de entorno
Utils::loadEnv(dirname(__DIR__, 2));

if ($argc < 3 || $argc > 4) {
    $fich = basename(__FILE__);
    echo <<< MARCA_FIN

    Usage: $fich <Result> <UserId> [<Timestamp>]

MARCA_FIN;
    exit(0);
}

$userName = $argv[1];
$email = $argv[2];
$password = $argv[3];

$entityManager = DoctrineConnector::getEntityManager();

/** @var User $user */
$user = $entityManager
    ->getRepository(User::class)
    ->findOneBy(['email' => $email]);

if ($user instanceof User) {
    echo "Usuario con $email ya existe" . PHP_EOL;
    exit(0);
}
$result = new User($userName, $email, $password);
try {
    if (!$user instanceof User) {
        echo "Se va a agregar ". $result->getUsername() . PHP_EOL;
        echo "Se va a agregar ". $result->getEmail() ." ya que no existe" . PHP_EOL;
        echo "Se va a agregar " . $result->getPassword() . PHP_EOL;

        $entityManager->persist($result);
        $entityManager->flush();
        echo 'Created User with ID ' . $result->getId()
            . ' USER ' . $user->getUsername() . PHP_EOL;
    }
} catch (Throwable $exception) {
    echo $exception->getMessage();
}

