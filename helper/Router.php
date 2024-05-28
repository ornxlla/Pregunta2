<?php

class Router
{private $defaultController;
    private $defaultMethod;

    public function __construct($defaultController, $defaultMethod)
    {
        $this->defaultController = $defaultController;
        $this->defaultMethod = $defaultMethod;
    }

    public function route($controllerName, $methodName, $username = null)
    {
        $controller = $this->getControllerFrom($controllerName);
        $this->executeMethodFromController($controller, $methodName, $username);
    }

    private function executeMethodFromController($controller, $method, $username = null)
    {
        $validMethod = method_exists($controller, $method) ? $method : $this->defaultMethod;
        if ($username) {
            call_user_func(array($controller, $validMethod), $username);
        } else {
            call_user_func(array($controller, $validMethod));
        }
    }
    private function getControllerFrom($module)
    {
        $controllerName = 'get' . ucfirst($module) . 'Controller';
        $validController = method_exists("Configuration", $controllerName) ? $controllerName : $this->defaultController;
        return call_user_func(array("Configuration", $validController));
    }


}