<?php

include_once('Configuration.php');

$configuration = new Configuration();
$router = $configuration->getRouter();

$module = $_GET['module'] ?? 'preguntados';
$method = $_GET['action'] ?? 'preguntados';

$router->route($module, $method);