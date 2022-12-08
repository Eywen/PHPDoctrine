<?php
/**
 * src/scripts/update_user.php
 */
require dirname(__DIR__, 2) . '/vendor/autoload.php';

use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;
use MiW\Results\Utility\Utils;

// Carga las variables de entorno
Utils::loadEnv(dirname(__DIR__, 2));

if ($argc < 1 || $argc > 6) {
    $fich = basename(__FILE__);
    echo <<< MARCA_FIN

    Usage: $fich <Result> <UserId> [<Timestamp>]

MARCA_FIN;
    exit(0);
}

$userId = (int)$argv[1];
$userName = $argv[2];
$email = $argv[3];
$password = $argv[4];
$enabled = $argv[5];

$entityManager = DoctrineConnector::getEntityManager();

/** @var User $user */
$user = $entityManager
    ->getRepository(User::class)
    ->findOneBy([ 'id' => $userId ]);
if (!$user instanceof User) {
    echo "Usuario $userId no encontrado" . PHP_EOL;
    exit(0);
}

echo "Se va a modificar usuario con id:". $userId . PHP_EOL;

for ($i = 2; $i < $argc; $i++) {
    // este punto para indicar en la ejecucion por consola del script que no queremos modificar ese parametro
    if ($argv[$i] != ".") {
        switch ($i) {
            case 2:
                $user->setUsername($argv[$i]);
                break;
            case 3:
                $user->setEmail($argv[$i]);
                break;
            case 4:
                $user->setPassword($argv[$i]);
                break;
            case 5:
                $user->setEnabled($argv[$i] == "true");
                break;
        }
    }
    $entityManager->flush();
}
echo PHP_EOL . sprintf(
        '  %2s: %20s %30s %7s' . PHP_EOL,
        'Id', 'Username:', 'Email:', 'Enabled:'
    );
/** @var User $user */
echo sprintf(
    '- %2d: %20s %30s %7s',
    $user->getId(),
    $user->getUsername(),
    $user->getEmail(),
    ($user->isEnabled()) ? 'true' : 'false'
),
PHP_EOL;

