<?php

/**
 * ResultsDoctrine - controllers.php
 *
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;
use MiW\Results\UserView;

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
    //var_dump($users);
    vistaListUSer($users);
}

function funcionUsuario(string $name): void
{
    echo $name." estoy en funcion usuario";
}

function crearUsuario(){

    echo " en crear usuario. Falta persisitir";

}

function list_action()
{
    //$posts = "Blanca";
    global $routes;
    global $posts;
    //$posts = $routes->get('ruta_user_list')->getPath();
    $entityManager = DoctrineConnector::getEntityManager();

    $userRepository = $entityManager->getRepository(User::class);
    $posts = $userRepository->findAll();
    //var_dump($posts);
    //require 'templates/list.php';

    //UserView::vista($posts);
    vistaListUSer($posts);
}

///////////////////////////  VISTAS  //////////////////////////////

function vistaListUSer($users): void
{
    echo <<< ____MARCA_FIN
        <style>
            table, th, td {
              border: 2px solid #96D4D4;
              border-collapse: collapse;
            }
        </style>
    ____MARCA_FIN;
    echo "<table style='width:70%'>
            <tr>
                <th><label>Nombre de usuario</label></th>
                <th><label>Email</label></th>
                <th><label>Habilitado</label></th>
                <th><label>Es Admin</label></th>
                <th colspan='3'><label>Operaciones</label></th>
            </tr>";
    foreach ($users as $user) {
        $username = $user->getUsername();
        $email = $user->getEmail();
        $enabled = $user->isEnabled();
        $isAdmin = $user->isAdmin();
        $url = '/users/' . urlencode($username);

        echo <<< ____MARCA_FIN
                <tr>
                    <td><label>$username</label></td>
                    <td><label>$email</label></td> 
                    <td><label>$enabled</label></td> 
                    <td><label>$isAdmin</label></td> 
                    <td><a href="$url">Ver Detalle</a></td>
                    <td><a href="">Eliminar</a></td>
                    <td><a href="">Modificar</a></td>
                </tr>
    ____MARCA_FIN;
    }

    global $routes;
    $rutaNewUSerForm = $routes->get('ruta_user_form')->getPath();
    //echo "<a href=$rutaNewUSerForm>Insertar nuevo usuario prueba con rutas href</a>";
    echo <<< ____MARCA_FIN
    <style>
      button {
        display: inline-block;
        background-color: #0000CD;
        border-radius: 10px;
        border: 4px double #cccccc;
        color: #F8F8FF;
        text-align: center;
        font-size: 15px;
        padding: 10px;
        width: 100px;
        -webkit-transition: all 0.5s;
        -moz-transition: all 0.5s;
        -o-transition: all 0.5s;
        transition: all 0.5s;
        cursor: pointer;
        margin: 5px;
      }
    </style>
    
        <form>
              <button type="button" onclick="window.location.href='$rutaNewUSerForm';" >new user</button>
        </form>
    ____MARCA_FIN;


}


function testfun()
{
    echo "Your test function on button click is working";
}



function vistaNewUser()
{
    global $routes;
    $ruta_user_new = $routes->get('ruta_user_new')->getPath();

    echo <<< ____MARCA_FIN
    
    <form method="post" action="$ruta_user_new">
        <h2>Creación de usuario</h2>
        <table>
            <tr><td><label>Nombre de usuario: </label></td> 
                <td><input type="text" name="username" required></td></tr>
            <tr><td><label>Email: </label></td> 
                <td><input type="email" name="email" required></td></tr>
            <tr><td><label>Contraseña: </label></td>
                <td><input type="password" name="password" required></td></tr>
            <tr><td><label>Habilitado: </label></td> 
                <td><input type="checkbox" name="enabled" checked></td></tr>
            <tr><td><label>Es Admin: </label></td> 
                <td><input type="checkbox" name="isAdmin"></td></tr>
            <tr><td colspan="2"><button type="submit">Crear</button></td></tr>
        </table>
    </form>
    ____MARCA_FIN;

}