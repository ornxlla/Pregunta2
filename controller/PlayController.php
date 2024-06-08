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

        $idUsuario = isset($_SESSION["id"]) ? $_SESSION["id"] : null;
        if (!isset($_SESSION["partida_iniciada"])) {

            $_SESSION ["partida_iniciada"] = true;
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
            $idPregunta = $_SESSION["pregunta_enviada"] ?? $_SESSION["pregunta_erronea"];
            unset($_SESSION["pregunta_enviada"]);
            unset($_SESSION["pregunta_erronea"]);

            $data["preguntas"] = $this->model->obtenerUnaPreguntaPorId($idPregunta);
            $data["respuesta"] = $this->model->obtenerRespuestasDeUnaPregunta($idPregunta);

            $data["error"] = $_SESSION["error"];
            unset($_SESSION["error"]);

            $data["habilitar_reporte"] = true;
        } else {
            if (!isset($_SESSION["pregunta_enviada"])) {
                $data["preguntas"] = $this->model->obtenerUnaPregunta($idPartidaIniciada, $idUsuario);
                $data["respuesta"] = $this->model->obtenerRespuestasDeUnaPregunta($data["pregunta"]["id"]);
                shuffle($data["respuestas"]);
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

        // Esto lo voy a necesitar casi siempre
        $idPartida = $_SESSION["idPartidaIniciada"];

        //------------------------------------------------------------//
        // Si se termino el tiempo, solo voy a mostrar un error, no que respuesta era la correcta, ya que en si el usuario no le erro, solo se quedo sin tiempo

        $tiempo_transcurrido = time() - $_SESSION["tiempo_inicial"];
        unset($_SESSION["tiempo_inicial"]);

        if ($tiempo_transcurrido >= 30) {
            $idPregunta = $_SESSION["pregunta_enviada"];
            /*unset($_SESSION["pregunta_enviada"]);*/

            $this->model->guardarPreguntaPorPartida($idPartida, $idPregunta);
            $this->model->aumentarContadorApariciones($idPregunta);
            $_SESSION["error"] = "Se acabó el tiempo";

        }

        //------------------------------------------------------------//
        // Si no falsearon la pregunta y el tiempo no se le termino, entonces seguimos -->
        $idPregunta = $_POST["id_pregunta"];
        unset($_SESSION["pregunta_enviada"]);
        //------------------------------------------------------------//

        $this->model->guardarPreguntaPorPartida($idPartida, $idPregunta);
        $this->model->aumentarContadorApariciones($idPregunta);

        if (isset($_POST["id_respuesta"])) {
            $valorRespuesta = $_POST["id_respuesta"];
            $posicionGuion = strpos($valorRespuesta, '-');
            $idRespuesta = substr($valorRespuesta, $posicionGuion + 1);
            $this->model->guardarRespuestaPorPartida($idPartida, $idRespuesta);

            // Si la variable valor respuesta contiene un 1- significa que es la correcta, asi que procesamos y vamos a partida denuevo
            if (str_contains($valorRespuesta, "1-")) {
                $this->model->aumentarCantidadDeAciertosDeLaPregunta($idPregunta);
                $this->model->sumarUnPunto($idPartida);
            }
        }

        //------------------------------------------------------------//
        // Si llegue aca significa que le erraron, asi que me guardo el id de la pregunta fallida y redirigo a partida
        $_SESSION["pregunta_erronea"] = $idPregunta;
        $_SESSION["error"] = "Respuesta incorrecta!";
    }
    public function finalizar_partida()
    {
        $idPartida = $_SESSION["idPartidaIniciada"];
        unset($_SESSION["idPartidaIniciada"]);
        unset($_SESSION["partida_iniciada"]);
        unset($_SESSION["pregunta_enviada"]);
        unset($_SESSION["tiempo_inicial"]);

        $this->model->actualizarPartida($idPartida);
        $this->model->calcularDificultadDelUsuario($_SESSION["id"]);
        $this->model->actualizarDificultadPreguntas();

        Redirect::to("homeUserLogueado");

    }

}

/*
class Timer
{
    private $start_time = null;
    private $end_time = null;

    public function start()
    {
        $this->start_time = microtime(true);
    }

    public function stop()
    {
        $this->end_time = microtime(true);
    }

    public function getElapsedTime()
    {
        if ($this->start_time === null) {
            throw new Exception('You must start the timer before getting the elapsed time');
        }

        $end_time = $this->end_time !== null ? $this->end_time : microtime(true);

        return $end_time - $this->start_time;
    }
}*/