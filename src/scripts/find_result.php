<?php
/**
 * src/scripts/find_result.php
 */

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use MiW\Results\Entity\Result;
use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;
use MiW\Results\Utility\Utils;

// Carga las variables de entorno
Utils::loadEnv(dirname(__DIR__, 2));

if ($argc < 1 || $argc > 3) {
    $fich = basename(__FILE__);
    echo <<< MARCA_FIN

    Usage: $fich <Result> <UserId> [<Timestamp>]

MARCA_FIN;
    exit(0);
}

$resultId = (int)$argv[1];

$entityManager = DoctrineConnector::getEntityManager();
echo "Busqueda de resultado con id: $resultId " . PHP_EOL;
/** @var User $user */
$result = $entityManager
    ->getRepository(Result::class)
    ->findOneBy([ 'id' => $resultId ]);

if (in_array('--json', $argv, true)) {
    echo json_encode($user, JSON_PRETTY_PRINT);
} else {
    if (!$result instanceof Result) {
        echo "Resultado con id $resultId no encontrado" . PHP_EOL;
        exit(0);
    } else {
        echo PHP_EOL . sprintf(
                '  %2s: %30s %20s %15s' . PHP_EOL,
                'Id', 'User', 'result', 'time'
            );
        /** @var Result $result */
        echo sprintf(
            '- %2d: %30s %20s %15s',
            $result->getId(),
            $result->getUser()->getId(),
            $result->getResult(),
              date_format($result->getTime(),"Y/m/d")
        ),
        PHP_EOL;
    }
}