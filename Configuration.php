<?php

use controller\UserController;
use model\UserModel;

include_once("controller/UserController.php");
include_once("model/UserModel.php");


include_once("helper/Database.php");
include_once("helper/Router.php");
include_once("helper/Redirect.php");
include_once("helper/Presenter.php");
include_once("helper/MustachePresenter.php");

include_once('vendor/mustache/src/Mustache/Autoloader.php');

class Configuration
{
    // CONTROLLERS
    public static function getUserController()
    {
        return new UserController(self::getPresenter(), self::getUserModel());
    }


    // MODELS
    private static function getUserModel()
    {
        return new UserModel(self::getDatabase());
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
        return new Router("getUserController", "getUsuario");
    }

    private static function getPresenter()
    {
        return new MustachePresenter("view/template");
    }
}