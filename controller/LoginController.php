<?php
class LoginController {
    public function index() {
        include 'view/LoginView.mustache';
    }

    public function login() {
        session_start();
        include 'configuration.php';
        include 'model/UserModel.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $usuario = $_POST['usuario'];
            $contraseña = $_POST['contraseña'];

            $usuarioModelo = new Usuario($conn);
            if ($usuarioModelo->login($usuario, $contraseña)) {
                $_SESSION['usuario'] = $usuario;
                header("Location: index.php");
            } else {
                echo "Usuario o contraseña incorrectos";
            }
        }
    }
}
?>
