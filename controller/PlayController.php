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

    public function get(){
        if(!isset($_SESSION["Session_id"])){
            //Error en el usuario de sesion - Directo al HOME
            header("Location: /");
        }

        if(isset($_SESSION["proxima_pregunta"])){
            //ESTA LOGICA ESTA RESERVADA CUANDO SE VIENE DE RESPONDER UNA PREGUNTA CORRECTA
            unset($_SESSION["proxima_pregunta"]); //EVITA LOCURAS CON F5
            unset($_SESSION["pregunta_enviada"]);
            unset($_SESSION["respuesta"]);
            unset($_SESSION["puntosPregunta"]);

            $idPartidaIniciada = $_SESSION["idPartida"];

        }else{
            //NUEVA PARTIDA
            if(isset($_SESSION["partida_iniciada"])){
                //Apreto "Nueva partida" al tener una partida en progreso o recargo la pagina. Se realiza el proceso de finalizar partida.
                $this->finalizar_partida();
                //header("Location: /");
                echo "adios!";
                return;
            }

            $_SESSION["partida_iniciada"] = true;
            $horaInicio = date('Y-m-d H:i:s');
            $idPartidaIniciada = $this->model->iniciarPartida_clasico($_SESSION["Session_id"], $horaInicio);

            $_SESSION["puntosPartida"] = 0;
            $_SESSION["idPartida"] = $idPartidaIniciada;
        }
        $data["aux_preguntas"] = $this->model->obtenerPreguntas_clasico($idPartidaIniciada, $_SESSION["Session_id"]);
        if(empty($data["aux_preguntas"]["listaPreguntas"])){
            echo "Se terminaron las preguntas!";
            //TO-DO Logica en caso de que se terminen las preguntas - Por ahora, terminara forzosamente la partida
            $this->finalizar_partida();
            return;
        }
        $data["aux_pregunta_elegida"] = $this->elegirPregunta($data["aux_preguntas"]["listaPreguntas"]);

        $data["pregunta"] = $this->unificarDatosPregunta($data["aux_pregunta_elegida"],$data["aux_preguntas"]["dificultadPreguntas"]);
        $data["respuesta"] = $this->model->obtenerRespuestas($data["pregunta"]["id_pregunta"]);
        shuffle($data["respuesta"]);

        $_SESSION["pregunta_enviada"] = $data["pregunta"]["id_pregunta"];
        $_SESSION["respuesta"] = $this->obtenerIdRespuestaCorrecta($data["respuesta"]);
        $_SESSION["puntosPregunta"] = $data["pregunta"]["puntos_pregunta"];

        $data["puntaje"] = $_SESSION["puntosPartida"];

        $this->presenter->render("playView", $data);
    }

    public function proximaPregunta(){
        if(isset($_POST["responder"])){
            if($_POST["id_respuesta"] == $_SESSION["respuesta"]){
                //SUMAR PUNTOS
                $_SESSION["puntosPartida"] = $_SESSION["puntosPartida"] + $_SESSION["puntosPregunta"];
                $_SESSION["proxima_pregunta"] = true;
                //Ingresar en bbdd que se respondio la pregunta.
                $aux = $this->model->preguntaRespondida($_SESSION["Session_id"], $_SESSION["idPartida"], $_SESSION["pregunta_enviada"], 1);

                Redirect::to("/play");
            }else{
                echo "Respuesta incorrecta!";
                $aux = $this->model->preguntaRespondida($_SESSION["Session_id"], $_SESSION["idPartida"], $_SESSION["pregunta_enviada"], 0);
                $this->finalizar_partida();
            }
        }
    }

    public function finalizar_partida()
    {
        $horafinal = date('Y-m-d H:i:s');
        $aux = $this->model->finalizarPartida($_SESSION["idPartida"], $_SESSION["puntosPartida"], $horafinal);
        $data["puntaje"] = $_SESSION["puntosPartida"];
        unset($_SESSION["partida_iniciada"]);
        unset($_SESSION["pregunta_enviada"]);
        unset($_SESSION["respuesta"]);
        unset($_SESSION["tiempo_inicial"]);
        unset($_SESSION["puntosPartida"]);
        $this->presenter->render("partidaTerminadaView", $data);
    }


    public function elegirPregunta($listaPreguntas){
        $numeroMagico = rand(0,99999);
        $idMagico = $numeroMagico % count($listaPreguntas);
        return $listaPreguntas[$idMagico];
    }

    public function unificarDatosPregunta($elegida, $lista){
        $datos = [];
        $datos["id_pregunta"] = $elegida["id_pregunta"];
        $datos["texto_pregunta"] = $elegida["texto"];
        $datos["tematica"] = $elegida["tematica"];

        for ($i = 0; $i < count($lista); $i++){
            if($lista[$i]["id_pregunta"] == $datos["id_pregunta"]){
                $datos["id_dificultad"] = $lista[$i]["dificultad"];
                $datos["texto_dificultad"] = $lista[$i]["texto_dificultad"];
                $datos["puntos_pregunta"] = $lista[$i]["puntos_correcto"];
            }
        }
        return $datos;
    }

    public function obtenerIdRespuestaCorrecta($respuesta){
        $returnValue = "";
        for($i = 0; $i < count($respuesta); $i++){
            if($respuesta[$i]["correcta"] == 1){
                $returnValue = $respuesta[$i]["id_respuesta"];
            }
        }
        return $returnValue;
    }



    /*---------------------------*/

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
}