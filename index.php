<?php
session_status();
include_once('Configuration.php');

$configuration = new Configuration();
$router = $configuration->getRouter();

$module = $_GET['module'] ?? 'preguntados';
$action = $_GET['action'] ?? 'toLogin'; // Acción predeterminada

if ($action === 'toRegistro') {
    // Si la acción es 'toRegistro', redirige a la página de registro
    header('Location: registroView.mustache');
    exit();
}

$username = $_GET['username'] ?? null;

if ($username) {
    $router->route($module, $action, $username);
} else {
    $router->route($module, $action);
}
?>
