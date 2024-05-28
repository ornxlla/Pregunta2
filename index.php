<?php
session_status();
include_once('Configuration.php');

$configuration = new Configuration();
$router = $configuration->getRouter();

$module = $_GET['module'] ?? 'preguntados';
$method = $_GET['action'] ?? 'getUsuario';
$username = $_GET['username'] ?? null;

if ($username) {
    $router->route($module, $method, $username);
} else {
    $router->route($module, $method);
}