<?php
include_once("third-party/phpqrcode/qrlib.php");
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
        if (isset($_SESSION["usuario"]) && isset($_SESSION["usuario"][0])) {
            $data["usuario"] = $_SESSION["usuario"][0];

            if (!isset($data["usuario"]["id_usuario"])) {
                error_log("ID de usuario no encontrado en la sesión.");
                return;
            }

            $imgQR = "http://localhost/PerfilUsuario/getUsuario?id=" . $data["usuario"]["id_usuario"];
            $carpeta_destino = "public/images/profile_qrs/";
            if (!file_exists($carpeta_destino)) {
                mkdir($carpeta_destino, 0777, true);
            }
            QRcode::png($imgQR, $carpeta_destino . $data["usuario"]["id_usuario"] . "_qr.png", QR_ECLEVEL_L, 4);
            $this->presenter->render("perfil-usuario", $data);
        } else {
            error_log("ID de usuario no encontrado");
        }
    }



    public function procesarModificacion()
    {
        if (isset($_SESSION["usuario"]) && isset($_SESSION["usuario"][0])) {
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
                    $data["usuario"] = $_SESSION["usuario"][0];

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
                        $_SESSION["usuario"][0] = $this->model->getUsuarioLogueado($username);
                        Redirect::to("/PerfilUsuario/getUsuario");
                    } else {
                        $data["error"] = "Los datos no pudieron ser ingresados";
                    }
                }
            }
            $data["usuario"] = $_SESSION["usuario"][0];
            $this->presenter->render("modificarUsuario", $data);

        }
    }




    public function modificarUsuario()
    {
        if (isset($_SESSION["usuario"])) {
            $this->presenter->render("modificarUsuario", ["usuario" => $_SESSION["usuario"]]);
        } else {
            error_log("No hay usuario logueado.");
        }
    }

    public function subirArchivo()
    {
        if (isset($_FILES["imagen"]["name"])) {
            $directorioImagen = "./public/img/users/";
            $nombreImagen = $_FILES["imagen"]["name"];
            $imagen = $directorioImagen . $nombreImagen;

            if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $imagen)) {
                return true;
            } else {
                return "Error al subir la imagen";
            }
        }
        return true;
    }
}
?>