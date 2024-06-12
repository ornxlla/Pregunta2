<?php
class LoginController
{
    private $model;
    private $presenter;

    public function __construct($presenter, $model)
    {
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function get()
    {
        $this->presenter->render("LoginView");
    }

    public function procesar()
    {
        if (isset($_POST["enviar"])) {
            if (isset($_POST["username"]) && isset($_POST["password"]) && !empty($_POST["username"]) && !empty($_POST["password"])) {
                $username = $_POST["username"];
                $password = $_POST["password"];
                $resultado = $this->model->validarCredenciales($username, $password);

                if (!empty($resultado)) {
                    $credencialesValidas = false;
                    foreach ($resultado as $fila) {
                        if ($fila["nombre_usuario"] === $username && $fila["contrasenia"] === $password) {
                            // Las credenciales son válidas
                            $credencialesValidas = true;
                            $id_usuario = $fila["id_usuario"];
                            // Iniciar sesión
                            $sesionIniciada = $this->iniciarSesion($id_usuario, $username, $password);
                            if ($sesionIniciada === PHP_SESSION_ACTIVE) {
                                $data["usuario"] = $this->model->getUsuario($username);
                                $_SESSION["usuario"] = $data["usuario"]; // Guardar datos del usuario en la sesión
                                $this->presenter->render("homeUserLogueado", $data);
                            } else {
                                $data["error"] = "Error al iniciar sesión";
                                $this->presenter->render("LoginView", $data);
                            }
                        }
                    }
                    // Si las credenciales no coinciden con ningún registro en la base de datos
                    if (!$credencialesValidas) {
                        $data["error"] = "Las credenciales son incorrectas!";
                        $this->presenter->render("LoginView", $data);
                    }
                } else {
                    // Si no se encontraron resultados en la base de datos
                    $data["error"] = "Las credenciales son incorrectas!";
                    $this->presenter->render("LoginView", $data);
                }
            } else {
                // Si los campos del formulario están vacíos
                $data["error"] = "Los campos no pueden estar vacíos";
                $this->presenter->render("LoginView", $data);
            }
        }
    }

    public function iniciarSesion($id, $username, $password)
    {
        $_SESSION["Session_id"] = $id;
        $_SESSION["Session_nombre"] = $username;
        return session_status();
    }

    public function getUsuario($username = null)
    {
        if (isset($_SESSION["usuario"])) {
            $data["usuario"] = $_SESSION["usuario"];
            $this->presenter->render("usuario", $data);
        } else {
            // Manejo del caso en que no se recibe el nombre de usuario
            $data["error"] = "Nombre de usuario no especificado";
            $this->presenter->render("error", $data);
        }
    }

    public function toHome() {
        if (isset($_SESSION["usuario"]) && isset($_SESSION["usuario"][0])) {
            $data["usuario"] = $_SESSION["usuario"];
            $this->presenter->render("homeUserLogueado", $data);
        }
    }

}


?>