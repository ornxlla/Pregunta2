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

    public function getRegistros(){
        $data["usuario"] = $this->model->getUsuarioRegistrados();
        $this->presenter->render("usuario", $data);
    }

    public function nuevoUsuario($nombre, $username, $year, $genero, $email, $password, $pais, $ciudad, $nombreImagen) {
        return $this->model->alta($nombre, $username, $year, $genero, $email, $password, $pais, $ciudad, $nombreImagen);
    }


    public function procesarAlta() {
        $data = array();
        if (isset($_POST["enviar"])) {
            if (!empty($_POST["nombre"]) && !empty($_POST["username"]) && !empty($_POST["year"])
                && !empty($_POST["genero"]) && !empty($_POST["email"]) && !empty($_POST["password"])
                && !empty($_POST["imagen"]) && !empty($_POST["pais"]) && !empty($_POST["ciudad"])) {
                $nombre = ucfirst($_POST["nombre"]);
                $username = strtolower($_POST["username"]);
                $year = $_POST["year"];
                $genero = $_POST["genero"];
                $email = $_POST["email"];
                $password = $_POST["password"];
                $pais = $_POST["pais"];
                $ciudad = $_POST["ciudad"];


                $directorioImagen = "./public/image/";
                $nombreImagen = $_FILES["imagen"]["name"];
                $imagen = $directorioImagen . $nombreImagen;

                if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $imagen)) {


                    $resultado = $this->nuevoUsuario($nombre, $username, $year, $genero, $email, $password, $pais, $ciudad, $nombreImagen);
                    if ($resultado) {
                        $data["altaOk"] = "Los datos fueron ingresados correctamente";
                    } else {
                        $data["error"] = "Los datos no pudieron ser ingresados";
                    }
                } else {
                    $data["error"] = "Error al subir la imagen";
                }
            } else {
                $data["error"] = "Los campos no pueden estar vacíos";
            }
            $this->presenter->render("subirPokemon", $data);
        }
    }

    public function mostrarPerfil(){
        $this->presenter->render("perfilUsuario");
    }

}