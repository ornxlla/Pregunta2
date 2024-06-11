<?php

class PlayController
{
    private $model;
    private $presenter;

    public function __construct($presenter, $model)
    {
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function toPlayView() {
        $this->presenter->render("playView");
    }

    public function getPartida()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $idUsuario = isset($_SESSION["Session_id"]) ? $_SESSION["Session_id"] : null;

        if (!isset($_SESSION["partida_iniciada"])) {
            $_SESSION["partida_iniciada"] = true;
            $horaInicio = date('Y-m-d H:i:s');
            $idPartidaIniciada = $this->model->iniciarPartida($idUsuario, $horaInicio);
            $_SESSION["idPartidaIniciada"] = $idPartidaIniciada;
        } else {
            //En el caso de que este en curso, solo guardamos el id en la variable
            $idPartidaIniciada = isset($_SESSION["idPartidaIniciada"]) ? $_SESSION["idPartidaIniciada"] : null;
        }

        //Lo utilizamos para validar el tiempo de respuesta
        if (!isset($_SESSION["tiempo_inicial"])) {
            $_SESSION["tiempo_inicial"] = time();
        }

        //Validamos a ver si le erraron para mostrar la pantalla de error en respuesta
        if (isset($_SESSION["error"])) {
            if (isset($_SESSION["pregunta_enviada"])) {
                unset($_SESSION["pregunta_enviada"]);
            }

            if (isset($_SESSION["pregunta_erronea"])) {
                unset($_SESSION["pregunta_erronea"]);
            }

            $data["error"] = $_SESSION["error"];
            unset($_SESSION["error"]);

            $data["habilitar_reporte"] = true;
        } else {
            unset($_SESSION["pregunta_enviada"]);
            if (!isset($_SESSION["pregunta_enviada"])) {
                $data["preguntas"] = $this->model->obtenerUnaPregunta($idPartidaIniciada, $idUsuario);
                $data["respuesta"] = $this->model->obtenerRespuestasDeUnaPregunta($data["preguntas"]["id_pregunta"]);
                shuffle($data["respuesta"]);

                $_SESSION["pregunta_enviada"] = $data["preguntas"]["id_pregunta"];
            } else {
                $idPregunta = $_SESSION["pregunta_enviada"];
                $data["preguntas"] = $this->model->obtenerUnaPreguntaPorId($idPregunta);
                $data["respuesta"] = $this->model->obtenerRespuestasDeUnaPregunta($idPregunta);
            }
        }

        if (!empty($data["error"])) {
            unset($_SESSION["tiempo_inicial"]);
        }
        $this->presenter->render("playView", $data);
    }
    public function validar_respuesta()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Esto lo voy a necesitar casi siempre
        $idPartida = $_SESSION["partida_iniciada"];

        //------------------------------------------------------------//
        // Si se terminó el tiempo, solo vamos a mostrar un error, no que respuesta era la correcta,
        // ya que en sí el usuario no le erró, solo se quedó sin tiempo

        $tiempo_transcurrido = time() - $_SESSION["tiempo_inicial"];
        unset($_SESSION["tiempo_inicial"]);

        if ($tiempo_transcurrido >= 30) {
            $idPregunta = $_SESSION["pregunta_enviada"];

            $this->model->guardarPreguntaPorPartida($idPartida, $idPregunta);
            $this->model->aumentarContadorApariciones($idPregunta);
            $_SESSION["error"] = "Se acabó el tiempo";

            // Redireccionar a la página de partida
            header("Location: /Play/getPartida");
            exit();
        }

        //------------------------------------------------------------//
        // Si no fallaron la pregunta y el tiempo no se les terminó, entonces seguimos
        if (isset($_POST["id_pregunta"])) {
            $idPregunta = $_POST["id_pregunta"];
            unset($_SESSION["pregunta_enviada"]);
            $this->model->guardarPreguntaPorPartida($idPartida, $idPregunta);
            $this->model->aumentarContadorApariciones($idPregunta);

            if (isset($_POST["id_respuesta"])) {
                $valorRespuesta = $_POST["id_respuesta"];
                $posicionGuion = strpos($valorRespuesta, '-');
                $idRespuesta = substr($valorRespuesta, $posicionGuion + 1);
                $this->model->guardarRespuestaPorPartida($idPartida, $idRespuesta);

                // Si la variable valor respuesta contiene un 1- significa que es la correcta
                if (str_contains($valorRespuesta, "1-")) {
                    $this->model->aumentarCantidadDeAciertosDeLaPregunta($idPregunta);
                    $this->model->sumarUnPunto($idPartida, $_SESSION["Session_id"]);

                    // Redireccionar a la página de partida para cargar una nueva pregunta
                    header("Location: /Play/getPartida");
                    exit();
                } else {
                    // Si la respuesta es incorrecta, mostrar un mensaje de "Partida terminada"
                    $_SESSION["error"] = "Partida terminada";
                    // Redireccionar a la página de partida
                    header("Location: /partidaTerminadaView");
                    exit();
                }
            }
        }
    }
    public function finalizar_partida()
    {
        $idPartida = $_SESSION["partida_iniciada"];
        unset($_SESSION["partida_iniciada"]);
        unset($_SESSION["pregunta_enviada"]);
        unset($_SESSION["tiempo_inicial"]);

        $this->model->actualizarPartida($idPartida);
        $this->model->calcularDificultadDelUsuario($_SESSION["Session_id"]);
        $this->model->actualizarDificultadPreguntas();

        // Redirigir a una página que muestre un mensaje de que la partida ha terminado
        header("Location: /partidaTerminadaView");
        exit();
    }

}