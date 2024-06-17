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
        if(isset($_SESSION["Session_id"])){
            $this->toHome();
        }else{
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
                        if ($fila["username"] === $username && $fila["password"] === $password ) {

                            $credencialesValidas = true;

                            if($fila["activado"] == 1){
                                $id_usuario = $fila["id_usuario"];
                                $correo = $fila["correo"];

                                $sesionIniciada = $this->iniciarSesion($id_usuario,$username,$correo);
                                if ($sesionIniciada === PHP_SESSION_ACTIVE) {
                                    header("location:/");
                                } else {
                                    $data["error"] = "Error al iniciar sesión.";
                                    $this->presenter->render("LoginView", $data);
                                }
                            }else{
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
                    var_dump($username);
                    var_dump($resultado);
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

    public function iniciarSesion($id,$username,$correo)
    {
        $_SESSION["Session_id"] = $id;
        $_SESSION["Session_username"] = $username;
        $_SESSION["Session_correo"] = $correo;
        return session_status();
    }

    public function toHome() {
        $data['usuario'] = $this->model->getDatosUser($_SESSION["Session_id"]);
        if(!empty($data['usuario'])){
            $this->presenter->render("homeUserLogueado", $data);
        }else{
            $this->cerrarSesion();
        }
    }

    public function cerrarSesion(){
        session_destroy();
        header("location:/");
    }


    ///   ******************PreguntaController**************************************************
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


    // consigna: Debe existir un tipo de usuario editor, que le permite dar de alta, baja y modificar las preguntas:

// 1ER PASO: CREAR PREGUNTA Y SE AGREGA AL LISTADO -- este metodo ok


    public function mostrarFormularioCrearPregunta() {
        $dificultades = $this->model->obtenerDificultades();
        $tematicas = $this->model->obtenerTematicas();

        $this->presenter->render('CrearPreguntaView', ['dificultades' => $dificultades,
            'tematicas' => $tematicas
        ]);
    }

    // ok creamos la pregunta y la misma se ve reflejada en el listado como incluida

    public function crearPregunta()
    {

            $pregunta_texto = $_POST['pregunta_texto'];
            $id_tematica = $_POST['id_tematica'];
            $id_dificultad = $_POST['id_dificultad'];

            $insertado = $this->model->insertarPregunta($pregunta_texto, $id_tematica, $id_dificultad);


        header('Location: /Login/listadoGeneralPreguntas');
        exit();

        }

// eliminar pregunta del listado

    public function eliminarPregunta()
    {
        $idPregunta = $_POST['id_pregunta'] ?? null;

        if (!$idPregunta) {
            echo "Error: ID de pregunta no especificado.";
            return;
        }

        $this->model->eliminarPregunta($idPregunta);

        header("Location: /Login/listadoGeneralPreguntas");
        exit();
    }








}


?>
