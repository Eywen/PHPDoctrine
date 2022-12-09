<?php

/**
 * src/scripts/delete_result.php
 */
require dirname(__DIR__, 2) . '/vendor/autoload.php';

use MiW\Results\Entity\Result;
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

$resultId = (int)$argv[1];

$entityManager = DoctrineConnector::getEntityManager();

/** @var Result $result */
$result = $entityManager
    ->getRepository(Result::class)
    ->findOneBy([ 'id' => $resultId ]);
if (!$result instanceof Result) {
    echo "Resultado $resultId no encontrado" . PHP_EOL;
    exit(0);
}

echo "Se va a eliminar resutado con id:". $resultId . PHP_EOL;

try {
    $entityManager->remove($result);
    $entityManager->flush();
} catch (Throwable $exception) {
    echo $exception->getMessage() . PHP_EOL;
}
