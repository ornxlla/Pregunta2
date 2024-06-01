<?php
session_status();
include_once('Configuration.php');

$configuration = new Configuration();
$router = $configuration->getRouter();

$module = $_GET['module'] ?? 'login';
$action = $_GET['action'] ?? 'toLogin'; // Acción predeterminada

switch ($action) {
    case 'registro':
        $module = "registro";
        $action = "get";
        break;
    case 'procesarAlta':
        $module = "registro";
        $action = "procesarAlta";
        break;
}
$username = $_GET['username'] ?? null;

if ($username) {
    $router->route($module, $action, $username);
} else {
    $router->route($module, $action);
}
?>
