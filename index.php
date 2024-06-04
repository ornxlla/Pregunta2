<?php
session_start();
include_once('Configuration.php');

$configuration = new Configuration();
$router = $configuration->getRouter();

$module = $_GET['controller'] ?? 'login';
$action = $_GET['method'] ?? 'get'; // Acción predeterminada

/*
switch ($action) {
    case 'registro':
        $module = "registro";
        $action = "get";
        break;
    case 'procesarAlta':
        $module = "registro";
        $action = "procesarAlta";
        break;
    case 'validacion':
        $module = "registro";
        $action = "validacion";
        break;
}*/

$username = $_GET['username'] ?? null;

$router->route($module, $action);

?>
