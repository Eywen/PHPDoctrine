<?php

/**
 * ResultsDoctrine - controllers.php
 *
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

use MiW\Results\Entity\User;
use MiW\Results\Entity\Result;
use MiW\Results\Utility\DoctrineConnector;
use MiW\Results\UserView;

function funcionHomePage(): void
{
    global $routes;

    $rutaListado = $routes->get('ruta_user_list')->getPath();
    $rutaListadoResultados = $routes->get('ruta_results_list')->getPath();
    echo <<< MARCA_FIN
    <ul>
        <li><a href="$rutaListado">Listado Usuarios</a></li>
        <li><a href="$rutaListadoResultados">Listado Resultados</a></li>
    </ul>
    MARCA_FIN;
}

function funcionListadoUsuarios(): void
{
    $entityManager = DoctrineConnector::getEntityManager();

    $userRepository = $entityManager->getRepository(User::class);
    $users = $userRepository->findAll();
    vistaListUSer($users);
}


function funcionNuevoUsuario(){
    $entityManager = DoctrineConnector::getEntityManager();
    if (isset($_POST) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        $newUser = new User($_POST['username'],$_POST['email'],$_POST['password']);

        if (isset($_POST['enabled'])) {
            $newUser->setEnabled(true);
        }
        if (isset($_POST['isAdmin'])) {
            $newUser->setIsAdmin(true);
        }
        try {
            $entityManager->persist($newUser);
            $entityManager->flush();
            echo " Usuario creado correctamente";
            var_dump($newUser);

        } catch (Throwable $exception) {
            echo " El usuario no se pudo crear";
            echo $exception->getMessage() . PHP_EOL;
        }
    } else {
        echo " El username, email y password son campos obligatorios";
    }
}

function funcionUpdateUsuario(string $name){
    echo " modificar $name";
    $entityManager = DoctrineConnector::getEntityManager();
    $user = $entityManager
        ->getRepository(User::class)
        ->findOneBy([ 'username' => $name ]);
    var_dump($user);

    if (isset($_POST)){
        if (isset($_POST['username'])) {
            $user->setUsername($_POST['username']);
        }
        if (isset($_POST['email'])) {
            $user->setEmail($_POST['email']);
        }
        if (isset($_POST['password'])) {
            $user->setPassword($_POST['password']);
        }
        if (isset($_POST['enabled'])) {
            $user->setUsername(true);
        }
        if (isset($_POST['isAdmin'])) {
            $user->setIsAdmin(true);
        }

        try {
            $entityManager->persist($user);
            $entityManager->flush();
            echo " Usuario modificado correctamente";
            var_dump($user);

        } catch (Throwable $exception) {
            echo " El usuario no se pudo modificar";
            echo $exception->getMessage() . PHP_EOL;
        }
    }
}

function funcionUsuario(string $name): void
{
    $entityManager = DoctrineConnector::getEntityManager();
    $user = $entityManager
        ->getRepository(User::class)
        ->findOneBy([ 'username' => $name ]);
    var_dump($user);
}

function findUserByEmail(string $email):void{
    $entityManager = DoctrineConnector::getEntityManager();
    echo "email: $email";
    $user = $entityManager
        ->getRepository(User::class)
        ->findOneBy([ 'email' => $email ]);
    var_dump($user);
}

function funcionEliminarUsuario ($name){
    echo "eliminado  $name"  . PHP_EOL;
    $entityManager = DoctrineConnector::getEntityManager();
    $user = $entityManager
        ->getRepository(User::class)
        ->findOneBy(['username' => $name]);
    try {
        $entityManager->remove($user);
        $entityManager->flush();
    } catch (Throwable $exception) {
        echo $exception->getMessage() . PHP_EOL;
    }
    echo "Usuario: " . $name . " eliminado correctamente: "  . PHP_EOL;
}

//--------------------------------Results------------------------------
function funcionListadoResultados(): void
{
    $entityManager = DoctrineConnector::getEntityManager();

    $resultsRepository = $entityManager->getRepository(Result::class);
    $results = $resultsRepository->findAll();
    echo "listado results "  . PHP_EOL;
    vistaListResults($results);
}

function funcionNuevoResultado(){

    if (isset($_POST) && isset($_POST['username']) && isset($_POST['result'])) {
        echo " Nuevo resultado. ";
        $resultado = $_POST['result'];
        $entityManager = DoctrineConnector::getEntityManager();
        $user = $entityManager
            ->getRepository(User::class)
            ->findOneBy(['username' => $_POST['username']]);
        $newResult = new Result($resultado,$user,new DateTime('now'));

        try {
            $entityManager->persist($newResult);
            $entityManager->flush();
            echo " Resultado creado correctamente";
            var_dump($newResult);
        } catch (Throwable $exception) {
            echo " El resultado no se pudo crear";
            echo $exception->getMessage() . PHP_EOL;
        }
    } else {
        echo " El user_id y resultado son campos obligatorios";
    }
}

//--------------------------------  VISTAS  ----------------------------

function vistaListUSer($users): void
{
    getTableStyle();
    echo "<table style='width:70%'>
            <tr>
                <th><label>Nombre de usuario</label></th>
                <th><label>Email</label></th>
                <th><label>Habilitado</label></th>
                <th><label>Es Admin</label></th>
                <th colspan='3'><label>Operaciones</label></th>
            </tr>";
    foreach ($users as $user) {
        $idUser = $user->getId();
        $username = $user->getUsername();
        $email = $user->getEmail();
        $enabled = $user->isEnabled();
        $isAdmin = $user->isAdmin();
        $url = '/users/' . urlencode($username);
        $urlemail = '/users/'.urlencode($email);
        $urlId = '/users/'.urlencode($idUser);


        echo <<< ____MARCA_FIN
                <tr>
                    <td><label>$username</label></td>
                    <td><label>$email</label></td> 
                    <td><label>$enabled</label></td> 
                    <td><label>$isAdmin</label></td> 
                    <td><a href="$url">Ver Detalle</a></td>                    
                    <td><a href="$url/delete">Eliminar</a></td>
                    <td><a href="$url/userformupdate">Modificar</a></td>
                </tr>
    ____MARCA_FIN;
    }

    global $routes;
    $rutaNewUSerForm = $routes->get('ruta_user_form')->getPath();
    $TextButton= "new user";
    getButtonNew($rutaNewUSerForm, $TextButton);
}

function testfun()
{
    echo "Your test function on button click is working";
}

function vistaNewUser()
{
    global $routes;
    $ruta_user_new = $routes->get('ruta_user_new')->getPath();
    getTableStyle();
    getUSerForm($ruta_user_new);
}

function vistaUpdateUser(string $name)
{
    echo "En vista update user";
    $ruta_user_update = "/users/$name/update";
    getTableStyle();
    getUSerFormUpdate($ruta_user_update,$name);
}

/**
 * @param string $ruta_user_new
 * @return void
 */
function getUSerForm(string $ruta_user_action): void
{

    echo <<< ____MARCA_FIN
    
    <form method="post" action="$ruta_user_action">
        <h2>Creación de usuario</h2>
        <table style='width:20%'>
            <tr><td><label>Nombre de usuario: </label></td> 
                <td><input type="text" name="username"  required></td></tr>
            <tr><td><label>Email: </label></td> 
                <td><input type="email" name="email"  required></td></tr>
            <tr><td><label>Contraseña: </label></td>
                <td><input type="password" name="password" required></td></tr>
            <tr><td><label>Habilitado: </label></td> 
                <td><input type="checkbox" name="enabled" ></td></tr>
            <tr><td><label>Es Admin: </label></td> 
                <td><input type="checkbox" name="isAdmin"  = ></td></tr>
            <tr><td colspan="2"><button type="submit">Crear</button></td></tr>
        </table>
    </form>
    ____MARCA_FIN;
}

function getUSerFormUpdate(string $ruta_user_action, string $name): void{

    $entityManager = DoctrineConnector::getEntityManager();
    $user = $entityManager
        ->getRepository(User::class)
        ->findOneBy(['username' => $name]);

    $username = $user->getUsername();
    $email = $user->getEmail() ;
    $enabled = $user->isEnabled() ? "checked" : "";
    $isAdmin = $user->isAdmin() ? "checked" : "";

    echo <<< ____MARCA_FIN
    
    <form method="post" action="$ruta_user_action">
        <h2>Creación de usuario</h2>
        <table style='width:20%'>
            <tr><td><label>Nombre de usuario: </label></td> 
                <td><input type="text" name="username" value="$username" ></td></tr>
            <tr><td><label>Email: </label></td> 
                <td><input type="email" name="email" value="$email" ></td></tr>
            <tr><td><label>Contraseña: </label></td>
                <td><input type="password" name="password" ></td></tr>
            <tr><td><label>Habilitado: </label></td> 
                <td><input type="checkbox" name="enabled" $enabled></td></tr>
            <tr><td><label>Es Admin: </label></td> 
                <td><input type="checkbox" name="isAdmin"  $isAdmin ></td></tr>
            <tr><td colspan="2"><button type="submit">Crear</button></td></tr>
        </table>
    </form>
    ____MARCA_FIN;
}

function vistaListResults($results): void
{
    getTableStyle();
    echo "<table style='width:70%'>
            <tr>
                <th><label>Resultado</label></th>
                <th><label>Usuario</label></th>
                <th><label>Fecha</label></th>
                <th colspan='3'><label>Operaciones</label></th>
            </tr>";
    foreach ($results as $result) {
        $resultName = $result->getResult();
        $username = $result->getUser()->getUsername();
        $time = $result->getTime()->format("c");
        $url = '/results/' . urlencode($resultName);


        echo <<< ____MARCA_FIN
                <tr>
                    <td><label>$resultName</label></td>
                    <td><label>$username</label></td> 
                    <td><label>$time</label></td>                     
                    <td><a href="$url">Ver Detalle</a></td>                    
                    <td><a href="$url/delete">Eliminar</a></td>
                    <td><a href="$url/resultformupdate">Modificar</a></td>
                </tr>
    ____MARCA_FIN;
    }

    global $routes;
    $rutaNewResultForm = $routes->get('ruta_result_form')->getPath();
    $TextButton= "new result";
    getButtonNew($rutaNewResultForm, $TextButton);
}

function vistaNewResult()
{
    global $routes;
    $ruta_result_new = $routes->get('ruta_result_new')->getPath();
    getTableStyle();
    getResultForm($ruta_result_new);
}

function getResultForm(string $ruta_result_new)
{
    $entityManager = DoctrineConnector::getEntityManager();
    $users = $entityManager
        ->getRepository(User::class)
        ->findAll();


    echo <<< ____MARCA_FIN
    
    <form method="post" action="$ruta_result_new">
        <h2>Creación de resultado</h2>
        <table style='width:15%'>
          
           <tr>
            <td><label>Usuario: </label></td> 
            <td><select name="username">
    ____MARCA_FIN;

    foreach ($users as $user) {
        $username = $user->getUsername();
        echo "<option value='$username'>$username</option>";
    }

    echo <<< ____MARCA_FIN
               </select>
            </td>
           </tr>
           <tr><td><label>Resultado: </label></td> 
                <td><input type="number" name="result"  required></td></tr>  
        <tr><td colspan="2"><button type="submit">Crear</button></td></tr>
    </table>
   </form>
____MARCA_FIN;
}

//-------------------------------------Html styles-----------------------------------

/**
 * @param string $rutaNewUSerForm
 * @param string $TextButton
 * @return void
 */
function getButtonNew(string $rutaNewUSerForm, string $TextButton): void
{
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
              <button type="button" onclick="window.location.href='$rutaNewUSerForm';" >$TextButton</button>
        </form>
    ____MARCA_FIN;
}

/**
 * @return void
 */
function getTableStyle(): void{

    echo <<< ____MARCA_FIN
        <style>
            table, th, td {
              border: 2px solid #96D4D4;
              border-collapse: collapse;
            }
        </style>
    ____MARCA_FIN;
}
