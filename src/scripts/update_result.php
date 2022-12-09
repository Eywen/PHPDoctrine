<?php
/**
 * src/scripts/update_result.php
 */
require dirname(__DIR__, 2) . '/vendor/autoload.php';

use MiW\Results\Entity\Result;
use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;
use MiW\Results\Utility\Utils;

// Carga las variables de entorno
Utils::loadEnv(dirname(__DIR__, 2));

if ($argc < 1 || $argc > 4) {
    $fich = basename(__FILE__);
    echo <<< MARCA_FIN

    Usage: $fich <Result> <UserId> [<Timestamp>]

MARCA_FIN;
    exit(0);
}

$resultId = (int)$argv[1];
$userId = (int)$argv[2];
$resultData = (int)$argv[3];
//$dataTime = $argv[4];

$entityManager = DoctrineConnector::getEntityManager();

/** @var Result $result */
$result = $entityManager
    ->getRepository(Result::class)
    ->findOneBy([ 'id' => $resultId ]);
if (!$result instanceof Result) {
    echo "Resultado $resultId no encontrado" . PHP_EOL;
    exit(0);
}

echo "Se va a modificar resultado con id:". $resultId . PHP_EOL;

for ($i = 2; $i < $argc; $i++) {
    // este punto para indicar en la ejecucion por consola del script que no queremos modificar ese parametro
    if ($argv[$i] != ".") {
        switch ($i) {
            case 2:
                $user = $entityManager
                    ->getRepository(User::class)
                    ->findOneBy([ 'id' => $userId ]);
                if (!$user instanceof User) {
                    echo "El Usuario $userId que se quiere asignar no ha sido encontrado" . PHP_EOL;
                    exit(0);
                }
                echo "El Usuario $userId que se quiere asignar ha sido encontrado " . PHP_EOL;
                $result->setUser($user);
                break;
            case 3:
                $result->setResult($argv[$i]);
                break;
            /*case 4:
                $result->setTime($argv[$i]);
                break;*/
        }
    }
    $entityManager->flush();
}
echo PHP_EOL . sprintf(
        '  %2s: %20s %30s %7s' . PHP_EOL,
        'Id', 'Username:', 'Email:', 'Enabled:'
    );
/** @var Result $result */
echo sprintf(
    '- %2d: %20s %30s %7s',
    $result->getId(),
    $result->getUser(),
    $result->getResult(),
    $result->getTime()

),
PHP_EOL;