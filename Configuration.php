<?php

use controller\RegistroController;
use controller\UserController;
use model\UserModel;

include_once("controller/LoginController.php");
include_once("controller/RegistroController.php");
include_once("model/LoginModel.php");
include_once("model/RegistroModel.php");


include_once("helper/Database.php");
include_once("helper/Router.php");
include_once("helper/Redirect.php");
include_once("helper/Presenter.php");
include_once("helper/MustachePresenter.php");

include_once('vendor/mustache/src/Mustache/Autoloader.php');

class Configuration
{
    // CONTROLLERS
    public static function getLoginController()
    {
        return new LoginController(self::getPresenter(), self::getLoginModel());
    }
    public static function getRegisterController()
    {
        return new RegistroController(self::getPresenter(), self::getRegistroModel());
    }


    // MODELS
    private static function getLoginModel()
    {
        return new LoginModel(self::getDatabase());
    }
    private static function getRegistroModel()
    {
        return new RegistroModel(self::getDatabase());
    }



    // HELPERS
    public static function getDatabase()
    {
        $config = self::getConfig();
        return new Database($config["servername"], $config["username"], $config["password"], $config["dbname"]);
    }

    private static function getConfig()
    {
        return parse_ini_file("config/globals.ini");
    }

    public static function getRouter()
    {
        return new Router("getLoginController", "getRouter");
    }

    private static function getPresenter()
    {
        return new MustachePresenter("view/template");
    }
}