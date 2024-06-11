<?php

class PerfilUsuarioController
{
    private $presenter;
    private $model;

    public function __construct($presenter, $model)
    {
        $this->presenter = $presenter;
        $this->model = $model;
    }


    public function getUsuario()
    {
        if (isset($_SESSION["usuario"])) {
            $data["usuario"] = $_SESSION["usuario"];
            $this->presenter->render("perfil-usuario", $data);
        } else {
            echo "ID de usuario no encontrado";
        }
    }

    public function procesarModificacion()
    {
        if (isset($_SESSION["usuario"])) {
            $data = [];
            if (isset($_POST["enviar"])) {
                $required_fields = ["nombre", "year", "genero", "email", "password", "latitud", "longitud"];
                $valid = true;
                foreach ($required_fields as $field) {
                    if (empty($_POST[$field])) {
                        $data["error"] = "Los campos no pueden estar vacíos";
                        $valid = false;
                        break;
                    }
                }
                if ($valid) {
                    $data["usuario"] = $_SESSION["usuario"];

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
                    $nombreImagen = isset($_FILES["imagen"]["name"]) ? $_FILES["imagen"]["name"] : null;

                    if ($nombreImagen) {
                        $upload_result = $this->subirArchivo();
                        if ($upload_result !== true) {
                            $data["error"] = $upload_result;
                            $this->presenter->render("modificarUsuario", $data);
                            return;
                        }
                    }


                    $resultado = $this->model->modificarUsuario($nombre, $username, $year, $genero, $email, $password, $pais, $ciudad, $nombreImagen, $latitud, $longitud);
                    if ($resultado) {
                        $_SESSION["usuario"] = $this->model->getUsuarioLogueado($username);
                        $this->presenter->render("perfil-usuario", $data);
                        Redirect::to("/PerfilUsuario/getUsuario");
                    } else {
                        $data["error"] = "Los datos no pudieron ser ingresados";
                    }
                }
            }
            // Renderizar la vista de modificarUsuario con los datos y errores
            $data["usuario"] = $_SESSION["usuario"];
            $this->presenter->render("modificarUsuario", $data);
        }
    }



    public function modificarUsuario()
    {
        if (isset($_SESSION["usuario"])) {
            $this->presenter->render("modificarUsuario", ["usuario" => $_SESSION["usuario"]]);
        } else {
            echo "No hay usuario logueado.";
        }
    }

    public function subirArchivo()
    {
        if (isset($_FILES["imagen"]["name"])) {
            $directorioImagen = "./public/img/users/";
            $nombreImagen = $_FILES["imagen"]["name"];
            $imagen = $directorioImagen . $nombreImagen;

            if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $imagen)) {
                return true; // La imagen se subió correctamente
            } else {
                return "Error al subir la imagen";
            }
        }
        return true; // No se proporcionó ninguna imagen
    }

}