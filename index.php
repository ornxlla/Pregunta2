<?php
session_start();
include_once('Configuration.php');

$configuration = new Configuration();
$router = $configuration->getRouter();

$module = $_GET['controller'] ?? 'login';
$action = $_GET['method'] ?? 'get'; // Acción predeterminada

if(strtolower($module) != 'login' && strtolower($module) != 'registro' && !isset($_SESSION["Session_id"])){ //No sesion y queriendo entrar a cualquier lado = Directo a Home
    $module = 'login';
    $action = 'get';
}
if(strtolower($module) == 'pregunta' && $action != "mostrarFormularioCrearPreguntaSugerida" && $action != 'crearPreguntaSugerida' &&  $_SESSION["Session_rol"] != 2){    //No rol de editor
    echo "<script>alert('Acceso denegado');</script>";
    $module = 'login';
    $action = 'get';
}

if(strtolower($module) == 'admin' &&  $_SESSION["Session_rol"] != 3){    //No rol de admin
    echo "<script>alert('Acceso denegado');</script>";
    $module = 'login';
    $action = 'get';
}

$router->route($module, $action);

?>
