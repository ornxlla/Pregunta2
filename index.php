<?php
session_start();
include 'configuration.php';

$controller = isset($_GET['controller']) ? $_GET['controller'] : 'login';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

$controllerFile = "controller/{$controller}Controller.php";

if (file_exists($controllerFile)) {

    include_once $controllerFile;

    $controllerClass = ucfirst($controller) . 'Controller';

    if (class_exists($controllerClass)) {
        $controllerObj = new $controllerClass();

        if (method_exists($controllerObj, $action)) {
            $controllerObj->$action();
        } else {
            echo "Error: Acción no encontrada.";
        }
    } else {
        echo "Error: Clase del controlador no encontrada.";
    }
} else {
    echo "Error: Controlador no encontrado.";
}
?>