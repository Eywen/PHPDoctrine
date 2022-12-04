<?php

/**
 * ResultsDoctrine - controllers.php
 *
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;

function funcionHomePage(): void
{
    global $routes;

    $rutaListado = $routes->get('ruta_user_list')->getPath();
    echo <<< MARCA_FIN
    <ul>
        <li><a href="$rutaListado">Listado Usuarios</a></li>
    </ul>
    MARCA_FIN;
}

function funcionListadoUsuarios(): void
{
    $entityManager = DoctrineConnector::getEntityManager();

    $userRepository = $entityManager->getRepository(User::class);
    $users = $userRepository->findAll();
    var_dump($users);
}

function funcionUsuario(string $name): void
{
    echo $name;
}
