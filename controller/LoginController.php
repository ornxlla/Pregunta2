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
                        if ($fila["nombre_usuario"] === $username && $fila["contrasenia"] === $password&& $fila["validado"] == 1) {

                            $credencialesValidas = true;
                            $id_usuario = $fila["id_usuario"];

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
                        $data["error"] = "Las credenciales son incorrectas o la cuenta no está validada.";
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







    ///   PreguntaController:
    ///    EL EDITOR puede revisar las preguntas reportadas, para aprobar o dar de baja
    ///    paso 1
    public function getPreguntasReportadas()
    {
        $preguntasReportadas = $this->model->obtenerPreguntasReportadas();
        $this->presenter->render('PreguntasReportadasView', ['preguntas' => $preguntasReportadas]);
    }

    // paso 2 de aprobar preguntas ok funciona

    public function aprobarPregunta()
    {

        $idPregunta = $_POST['id_pregunta'] ?? null;

        if (!$idPregunta) {
            echo "Error: ID de pregunta no especificado.";
            return;
        }
        $this->model->aprobarPregunta($idPregunta);
        header("Location: /Login/getPreguntasReportadas");
        exit;
    }


    // paso 3 dar de baja preguntada reportada ok funciona

    public function darDeBajaPregunta()
    {
        $idPregunta = $_POST['id_pregunta'] ?? null;

        if (!$idPregunta) {
            echo "Error: ID de pregunta no especificado.";
            return;
        }
        $this->model->darDeBajaPregunta($idPregunta);
        header("Location: /Login/getPreguntasReportadas");
        exit;
    }


  // el editor puede aprobar las preguntas sugeridas por usuarios
  // paso 1
    public function getPreguntasSugeridas()
    {
        $preguntasSugeridas = $this->model->obtenerPreguntasSugeridas();
        $this->presenter->render('PreguntasSugeridasView', ['preguntas' => $preguntasSugeridas]);
    }

// paso 2 que apruebe preguntas sugeridas

    public function aprobarPreguntaSugerida()
    {
        $idPregunta = $_POST['id_pregunta'] ?? null;

        if (!$idPregunta) {
            echo "Error: ID de pregunta no especificado.";
            return;
        }

        $this->model->aprobarPreguntaSugerida($idPregunta);
        header("Location: /Login/getPreguntasSugeridas");
        exit;
    }

// el editor podrá ver los cambios de sus funciones en el listado general de preguntas, es decir que trae las preguntas que no son sugeridas ni reportadas  (porque cuando las aceptó dichos campos pasaron a estar en false)

    public function listadoGeneralPreguntas()
    {
        $preguntasGenerales = $this->model->obtenerPreguntasGenerales();
        $this->presenter->render('ListadoGeneralPreguntasView', ['preguntas' => $preguntasGenerales]);
    }


}


?>