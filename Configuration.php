<?php

use controller\RegistroController;
use model\RegistroModel;
//use controller\LoginController;
//use model\LoginModel;

//use controller\PlayController;
//use model\PlayModel;

//use controller\AdminController
//user model\AdminModel

include_once("controller/LoginController.php");
include_once("controller/RegistroController.php");
include_once("controller/PerfilUsuarioController.php");
include_once ("controller/PlayController.php");
include_once("controller/PreguntaController.php");
include_once ("controller/RankingController.php");
include_once("controller/AdminController.php");
include_once("model/LoginModel.php");
include_once("model/RegistroModel.php");
include_once("model/PerfilUsuarioModel.php");
include_once("model/PlayModel.php");
include_once("model/PreguntaModel.php");
include_once("model/RankingModel.php");
include_once("model/AdminModel.php");

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

    public static function getRegistroController()
    {
        return new RegistroController(self::getPresenter(), self::getRegistroModel());
    }

    public static function getPerfilUsuarioController()
    {
        return new PerfilUsuarioController(self::getPresenter(), self::getPerfilUsuarioModel());
    }

    public static function getPlayController()
    {
        return new PlayController(self::getPresenter(), self::getPlayModel());
    }
    public static function getRankingController()
    {
        return new RankingController(self::getPresenter(), self::getRankingModel());
    }
    public static function getPreguntaController()
    {
        return new PreguntaController(self::getPresenter(), self::getPreguntaModel());
    }

    public static function getAdminController()
    {
        return new AdminController(self::getPresenter(), self::getAdminModel());
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

    private static function getPerfilUsuarioModel()
    {
        return new PerfilUsuarioModel(self::getDatabase());
    }

    private static function getPlayModel()
    {
        return new PlayModel(self::getDatabase());
    }

    private static function getPreguntaModel()
    {
        return new PreguntaModel(self::getDatabase());
    }


    private static function getRankingModel()
    {
        return new RankingModel(self::getDatabase());
    }

    private static function getAdminModel()
    {
        return new AdminModel(self::getDatabase());
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

   // $pdfCreator= new PdfCreator();

}