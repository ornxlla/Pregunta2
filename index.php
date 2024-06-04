<?php
session_start();
include_once('Configuration.php');

$configuration = new Configuration();
$router = $configuration->getRouter();

$module = $_GET['controller'] ?? 'login';
$action = $_GET['method'] ?? 'get'; // Acción predeterminada


$username = $_GET['username'] ?? null;

$router->route($module, $action);

?>
