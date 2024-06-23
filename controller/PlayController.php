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

        $data["pregunta"] = $this->unificarDatosPregunta($data["aux_pregunta_elegida"]);
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

    public function unificarDatosPregunta($elegida){
        $datos = [];
        $datos["id_pregunta"] = $elegida["id_pregunta"];
        $datos["texto_pregunta"] = $elegida["texto"];
        $datos["tematica"] = $elegida["tematica"];
        $aux = $this->model->obtenerDificultadPreguntas($elegida["id_pregunta"]);
        $datos["id_dificultad"] = $aux[0]["dificultad"];
        $datos["texto_dificultad"] = $aux[0]["texto_dificultad"];
        $datos["puntos_pregunta"] = $aux[0]["puntos_correcto"];
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

    /*--APARTADO DUELO--*/

    public function playDuelo(){
        echo $_SESSION["Session_id"] . " Vs " . $_SESSION["duelo_id"];
    }

    public function finalizar_duelo(){
        unset($_SESSION["Session_id"]);
    }
}