<?php
namespace controller;

class RegistroController
{
    private $model;
    private $presenter;

    public function __construct($presenter, $model){
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function get(){
        $this->presenter->render("registroView");
    }

    public function nuevoUsuario($nombre, $username, $year, $genero, $email, $password, $pais, $ciudad, $nombreImagen, $latitud, $longitud) {
        // Primero verificamos si el usuario ya existe
        $usuarioExistente = $this->model->obtenerUsuario($username);
        if ($usuarioExistente) {
            return false;
        }

        $codigoValidacion = $this->getNumeroValidacion();

        $resultadoLogin = $this->model->altaUsuario($username, $password, $email, $codigoValidacion);
        if($resultadoLogin){

            $datosCreados = $this->model->obtenerUsuario($username);
            if(isset($datosCreados["id_usuario"])){

                $resultadoDatos = $this->model->altaUsuario_datos($datosCreados["id_usuario"], $nombre, $year, $genero, $nombreImagen, $pais, $ciudad, $latitud, $longitud);
                if($resultadoDatos){

                    $envioCorreo = $this->model->enviarCorreoConfirmacion($email, $codigoValidacion);
                    return true;
                }
            }
        }
        return false;
    }

    public function procesarAlta() {
        $data = [];
        if (isset($_POST["enviar"])) {
            if (!empty($_POST["nombre"]) && !empty($_POST["username"]) && !empty($_POST["year"])
                && !empty($_POST["genero"]) && !empty($_POST["email"]) && !empty($_POST["password"])
                && !empty($_POST["latitud"]) && !empty($_POST["longitud"])) {

                $nombre = ucfirst($_POST["nombre"]);
                $username = strtolower($_POST["username"]);
                $year = $_POST["year"];
                $genero = $this->obtenerLetraGenero($_POST["genero"]);
                $email = $_POST["email"];
                $password = $_POST["password"];
                $latitud = $_POST["latitud"];
                $longitud = $_POST["longitud"];
                $pais = $_POST["pais"];
                $ciudad = $_POST["ciudad"];

                $estadoImagen = $this->subirArchivo();
                if ($estadoImagen != 2) {
                    $nombreImagen = $_FILES["imagen"]["name"];
                    $resultado = $this->nuevoUsuario($nombre, $username, $year, $genero, $email, $password, $pais, $ciudad, $nombreImagen, $latitud, $longitud);
                    if ($resultado) {
                        $data["altaOk"] = "Los datos fueron ingresados correctamente. Se ha enviado un correo electrónico de confirmación.";
                        $this->presenter->render("usuarioRegistradoView", $data);
                    } else {
                        $data["error"] = "No se pudo enviar el correo electrónico de confirmación. ";
                        $this->presenter->render("registroView", $data);
                    }
                } else {
                    $data["error"] = "Los datos no pudieron ser ingresados";
                    $this->presenter->render("registroView", $data);
                }
            } else {
                $data["error"] = "Los campos no pueden estar vacíos";
                $this->presenter->render("registroView", $data);
            }
        }
    }


    public function getNumeroValidacion(){
        $timestamp = time();
        $numAleatorio=rand(1000,9999);
        $codigo = $timestamp . $numAleatorio;
        return $codigo;
    }

    public function validacion(){
        if(isset($_GET["code"])){
            $code = $_GET["code"];
            $usuarioValidado = $this->model->validarUsuario($code);
            if ($usuarioValidado) {
                $this->presenter->render("bienvenidaUserValidado");
            } else {
                // o ponemos un error
                Redirect::root();
            }
        }
    }


    public function subirArchivo(){
        if(isset($_FILES["imagen"]["name"])){
            $directorioImagen = "./public/img/users/";
            $nombreImagen = $_FILES["imagen"]["name"];
            $imagen = $directorioImagen . $nombreImagen;

            if(move_uploaded_file($_FILES["imagen"]["tmp_name"], $imagen) ){
                return 1; // STATUS 1 - Se subio el archivo correctamente
            }else{
                return 2; // STATUS 2 - Error al subir archivo
            }
        }
        return 4;   //STATUS 4 - No tiene imagen
    }

    public function mostrarPerfil(){
        $this->presenter->render("perfilUsuario");
    }


    public function obtenerLetraGenero($genero){
        switch($genero){
            case "Masculino":
                return "M";
                break;
            case "Femenino":
                return "F";
                break;
            default:
                return "X";
        }
    }

}