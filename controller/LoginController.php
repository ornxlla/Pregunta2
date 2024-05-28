<?php
class LoginController {
    private $model;
    private $presenter;

    public function __construct($presenter, $model){
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function procesar()
    {
        if (isset($_POST["enviar"])) {
            if (isset($_POST["username"]) && isset($_POST["password"]) && !empty($_POST["username"]) && !empty($_POST["password"])) {
                $username = $_POST["username"];
                $password = $_POST["pw"];
                $resultado = $this->model->validarCredenciales($username, $password);

                if (!empty($resultado)) {
                    $credencialesValidas = false;
                    foreach ($resultado as $fila) {
                        if ($fila["username"] === $username && $fila["password"] === $password) {
                            // Las credenciales son válidas
                            $credencialesValidas = true;
                            // Iniciar sesión
                            $sesionIniciada = $this->iniciarSesion($username, $password);
                            if ($sesionIniciada === PHP_SESSION_ACTIVE) {
                                    $data["usuario"] = $this->model->getUsuario($username);
                                    $this->presenter->render("homeUserLogueado", $data);

                            } else {
                                $data["error"] = "Error al iniciar sesión";
                            }

                        }
                    }
                    // Si las credenciales no coinciden con ningún registro en la base de datos
                    if (!$credencialesValidas) {
                        $data["error"] = "Las credenciales son incorrectas";
                        $this->presenter->render("iniciarSesion", $data);
                    }
                } else {
                    // Si no se encontraron resultados en la base de datos
                    $data["error"] = "Las credenciales son incorrectas";
                    $this->presenter->render("iniciarSesion", $data);
                }
            } else {
                // Si los campos del formulario están vacíos
                $data["error"] = "Los campos no pueden estar vacíos";
                $this->presenter->render("iniciarSesion", $data);
            }
        }
    }
    public function iniciarSesion($username, $password)
    {
        $_SESSION["nombreUser"] = $username;
        $_SESSION["pw"] = $password;
        return session_status();
    }

    public function getUsuario($username = null){
        if ($username) {
            $data["usuario"] = $this->model->getUsuario($username);
            $this->presenter->render("usuario", $data);
        } else {
            // Manejo del caso en que no se recibe el nombre de usuario
            $data["error"] = "Nombre de usuario no especificado";
            $this->presenter->render("error", $data);
        }
    }
   /* public function cerrarSesion()
    {
        session_destroy();
        Redirect::root();
    }*/

}
?>
