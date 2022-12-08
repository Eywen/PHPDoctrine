<?php
/**
* src/scripts/delete_user.php
*/
require dirname(__DIR__, 2) . '/vendor/autoload.php';

use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;
use MiW\Results\Utility\Utils;

// Carga las variables de entorno
Utils::loadEnv(dirname(__DIR__, 2));

if ($argc < 1 || $argc > 2) {
    $fich = basename(__FILE__);
    echo <<< MARCA_FIN

    Usage: $fich <Result> <UserId> [<Timestamp>]

MARCA_FIN;
    exit(0);
}

$userId = (int)$argv[1];

$entityManager = DoctrineConnector::getEntityManager();

/** @var User $user */
$user = $entityManager
    ->getRepository(User::class)
    ->findOneBy([ 'id' => $userId ]);
if (!$user instanceof User) {
    echo "Usuario $userId no encontrado" . PHP_EOL;
    exit(0);
}

echo "Se va a eliminar usuario con id:". $userId . PHP_EOL;

try {
    $entityManager->remove($user);
    $entityManager->flush();
} catch (Throwable $exception) {
    echo $exception->getMessage() . PHP_EOL;
}
