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
        $entityManager->persist($result);
        $entityManager->flush();
    }
} catch (Throwable $exception) {
    echo $exception->getMessage();
}
$userCreated = $entityManager
    ->getRepository(User::class)
    ->findOneBy(['email' => $email]);
echo PHP_EOL . sprintf(
        '  %2s: %20s %30s %7s' . PHP_EOL,
        'Id', 'Username:', 'Email:', 'Enabled:'
    );
/** @var User $userCreated */
echo sprintf(
    '- %2d: %20s %30s %7s',
    $userCreated->getId(),
    $userCreated->getUsername(),
    $userCreated->getEmail(),
    ($userCreated->isEnabled()) ? 'true' : 'false'
),
PHP_EOL;

