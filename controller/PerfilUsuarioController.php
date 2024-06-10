<?php

class PerfilUsuarioController
{
    private $presenter;
    private $model;

    public function __construct($presenter, $model){
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function toPerfilUsuario(){
        $this->presenter->render("perfil-usuario");
    }

    public function getUsuario()
    {
        if (isset($_GET['nombre_usuario'])) {
            $username = $_GET['nombre_usuario'];
            $data["usuario"] = $this->model->getUsuarioLogueado($username);
            if ($data["usuario"]) {
                $this->presenter->render("perfil-usuario", $data);
            } else {
                echo "Usuario no encontrado.";
            }
        } else {
            echo "ID de usuario no encontrado";
        }
    }
    /*METODOS DE PLAY, se ponen aca porque para que se pueda acceder
    a play se tiene que estar logueado o algo esta mal relacionado? SOLO ASI ME ANDA


      public function saludaPlay(){
        echo "hola play";

    }

    public function getVistaPlay(){
        $this->presenter->render("playView");

    }*/
    public function usuarioModificado($nombre, $username, $year, $genero, $email, $password, $pais, $ciudad, $nombreImagen, $latitud, $longitud)
    {
        return $this->model->modificarUsuario($nombre, $username, $year, $genero, $email, $password, $pais, $ciudad, $nombreImagen, $latitud, $longitud);
    }


    public function procesarModificacion()
    {
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
                $pais = $_POST["pais"];
                $ciudad = $_POST["ciudad"];
                $directorioImagen = "./public/img/users/";
                $nombreImagen = isset($_FILES["imagen"]["name"]) ? $_FILES["imagen"]["name"] : null;

                if ($nombreImagen) {
                    $usuarionImg = $directorioImagen . $nombreImagen;
                    if (!move_uploaded_file($_FILES["imagen"]["tmp_name"], $usuarionImg)) {
                        $data["error"] = "Error al subir la imagen";
                        $this->presenter->render("modificarUsuario", $data);
                        return;
                    }
                }

                $resultado = $this->usuarioModificado($nombre, $username, $year, $genero, $email, $password, $pais, $ciudad, $nombreImagen, $latitud, $longitud);
                if ($resultado) {
                    $data["altaOk"] = "Los datos fueron ingresados correctamente";
                    Redirect::to("/PerfilUsuario/getUsuario?nombre_usuario=" . $username);
                } else {
                    $data["error"] = "Los datos no pudieron ser ingresados";
                }
            } else {
                $data["error"] = "Los campos no pueden estar vacíos";
            }
            $data["usuario"] = $this->model->getUsuarioLogueado(($_POST["nombre_usuario"]));
            $this->presenter->render("modificarUsuario", $data);
        }
    }
    public function modificarUsuario()
    {
        if (isset($_GET["nombre_usuario"])) {
            $username = $_GET["nombre_usuario"];
            $usuario = $this->model->getUsuarioLogueado($username);
            if ($usuario) {
                $this->presenter->render("modificarUsuario", ["usuario" => $usuario]);
            } else {
                echo "Usuario no encontrado.";
            }
        } else {
            echo "Nombre de usuario no proporcionado.";
        }
    }


    private function subirArchivo()
    {
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
        return 4;
    }


}