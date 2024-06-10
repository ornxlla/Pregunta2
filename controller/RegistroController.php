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

    public function saluda(){
        echo "hola";
    }
    public function getRegistros(){
        $data["usuario"] = $this->model->getUsuarioRegistrados();
        $this->presenter->render("usuario", $data);
    }

    public function nuevoUsuario($nombre, $username, $year, $genero, $email, $password, $pais, $ciudad, $nombreImagen, $latitud, $longitud) {
        return $this->model->darDeAltaUsuario($nombre, $username, $year, $genero, $email, $password, $nombreImagen,  $pais, $ciudad, $latitud, $longitud);
    }

    public function procesarAlta() {
        $data = array();
        if (isset($_POST["enviar"])) {
            if (!empty($_POST["nombre"]) && !empty($_POST["username"]) && !empty($_POST["year"])
                && !empty($_POST["genero"]) && !empty($_POST["email"]) && !empty($_POST["password"])
                && !empty($_POST["latitud"]) && !empty($_POST["longitud"])) {
                $nombre = ucfirst($_POST["nombre"]);
                $username = strtolower($_POST["username"]);
                $year = $_POST["year"];
                $genero = $_POST["genero"];
                $email = $_POST["email"];
                $password = $_POST["password"];
                $latitud = $_POST["latitud"];
                $longitud = $_POST["longitud"];
                //$pais = $_POST["pais"];
                //$ciudad = $_POST["ciudad"];
                //PARA TESTING
                $pais = "Argentina";
                $ciudad = "Buenos Aires";
                $estadoImagen = $this->subirArchivo();

                if ($estadoImagen != 2) {
                    $nombreImagen = $_FILES["imagen"]["name"];
                    $resultado = $this->nuevoUsuario($nombre, $username, $year, $genero, $email, $password, $pais, $ciudad, $nombreImagen, $latitud, $longitud);
                    if ($resultado) {
                        $data["altaOk"] = "Los datos fueron ingresados correctamente";
                        $data["numVal"] = $this->getNumeroValidacion($username);
                        $this->presenter->render("usuarioRegistradoView", $data);
                    } else {
                        $data["error"] = "Los datos no pudieron ser ingresados";
                        $this->presenter->render("registroView", $data);
                    }
                } else {
                    $data["error"] = "Error al subir la imagen";
                    $this->presenter->render("registroView", $data);
                }
            } else {
                $data["error"] = "Los campos no pueden estar vacíos - " . $_POST["nombre"] . " - " . $_POST["username"] . " - " . $_POST["year"] . " - " . $_POST["genero"] . " - " . $_POST["email"] . " - " . $_POST["password"] . " - " . $_POST["imagen"] . " - " . $_POST["latitud"] ." - " . $_POST["longitud"];
                $this->presenter->render("registroView", $data);
            }

        }
    }

    public function getNumeroValidacion($username){
        return $username;
    }

    public function validacion(){
        if(isset($_GET["code"])){
            //TO-DO - Toda la logica de validacion
            $code = $_GET["code"];
            $data["validar"] = $code;
            $this->presenter->render("usuarioRegistradoView", $data);
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

}