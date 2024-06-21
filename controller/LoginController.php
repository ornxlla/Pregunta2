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
        if (isset($_SESSION["Session_id"])) {
            $this->toHome();
        } else {
            $this->presenter->render("LoginView");
        }
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
                        if ($fila["username"] === $username && $fila["password"] === $password) {

                            $credencialesValidas = true;

                            if ($fila["activado"] == 1) {
                                $id_usuario = $fila["id_usuario"];
                                $correo = $fila["correo"];
                                $rol = $fila["rol"];


                                $sesionIniciada = $this->iniciarSesion($id_usuario, $username, $correo, $rol);
                                if ($sesionIniciada === PHP_SESSION_ACTIVE) {

                                    $this->redireccionarPorRol($rol);
                                } else {
                                    $data["error"] = "Error al iniciar sesión.";
                                    $this->presenter->render("LoginView", $data);
                                }
                            } else {
                                $data["error"] = "¡Usuario no validado! Por favor, revise su casilla de correo.";
                                $this->presenter->render("LoginView", $data);
                            }

                        }
                    }
                    // Si las credenciales no coinciden con ningún registro en la base de datos
                    if (!$credencialesValidas) {
                        $data["error"] = "Las credenciales son incorrectas.";
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

    public function iniciarSesion($id, $username, $correo, $rol)
    {
        $_SESSION["Session_id"] = $id;
        $_SESSION["Session_username"] = $username;
        $_SESSION["Session_correo"] = $correo;
        $_SESSION["Session_rol"] = $rol; // Añadir el rol a la sesión
        return session_status();
    }

    public function toHome()
    {
        $data['usuario'] = $this->model->getDatosUser($_SESSION["Session_id"]);
        if (!empty($data['usuario'])) {
            $this->presenter->render("homeUserLogueado", $data);
        } else {
            $this->cerrarSesion();
        }
    }

    public function cerrarSesion()
    {
        session_destroy();
        header("location:/");
    }

    public function redireccionarPorRol($rol)
    {
        switch ($rol) {
            case 1:
                header("Location: /homeUserLogueado");
                break;
            case 2:
                $this->presenter->render("homeEditor");
                break;
            case 3:
                $this->presenter->render("homeAdmin");
                break;
            default:
                header("Location: /LoginView");
                break;
        }
        exit();
    }
}


?>
