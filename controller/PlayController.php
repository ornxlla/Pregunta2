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
                return;
            }

            $_SESSION["partida_iniciada"] = true;
            $horaInicio = date('Y-m-d H:i:s');
            $idPartidaIniciada = $this->model->iniciarPartida_clasico($_SESSION["Session_id"], $horaInicio);
            $dificultadUsuario = $this->model->obtenerDificultadUsuario($_SESSION["Session_id"]);

            $_SESSION["dificultadUsuario"] = $dificultadUsuario;
            $_SESSION["puntosPartida"] = 0;
            $_SESSION["idPartida"] = $idPartidaIniciada;
        }
        $data["aux_preguntas"] = $this->model->obtenerPreguntas_clasico($idPartidaIniciada, $_SESSION["Session_id"],$_SESSION["dificultadUsuario"]);
        if(empty($data["aux_preguntas"]["listaPreguntas"])){
            echo "Se terminaron las preguntas!";
            //TO-DO Logica en caso de que se terminen las preguntas - Por ahora, terminara forzosamente la partida
            $this->finalizar_partida();
            return;
        }
        if($_SESSION["puntosPartida"] > 300){
            $_SESSION["dificultadUsuario"] = $this->actualizarDificultad($_SESSION["puntosPartida"],  $_SESSION["dificultadUsuario"] );
        }
        $data["aux_pregunta_elegida"] = $this->elegirPregunta($data["aux_preguntas"]["listaPreguntas"]);

        $data["pregunta"] = $this->unificarDatosPregunta($data["aux_pregunta_elegida"]);
        $data["respuesta"] = $this->model->obtenerRespuestas($data["pregunta"]["id_pregunta"]);
        shuffle($data["respuesta"]);

        $_SESSION["pregunta_enviada"] = $data["pregunta"]["id_pregunta"];
        $_SESSION["respuesta"] = $this->obtenerIdRespuestaCorrecta($data["respuesta"]);
        $_SESSION["puntosPregunta"] = $data["pregunta"]["puntos_pregunta"];

        $data["puntaje"]["puntaje"] = $_SESSION["puntosPartida"];

        $this->presenter->render("playView", $data);
    }

    public function proximaPregunta(){
        if(isset($_POST["responder"])){
            if(isset($_SESSION["idDuelo"]) && isset($_SESSION["rival_id"]) ){   //DUELO
                if($_POST["id_respuesta"] == $_SESSION["duelo_respuesta"]){
                    //SUMAR PUNTOS A JUGADOR 1
                    $_SESSION["puntosJug1"] = $_SESSION["puntosJug1"] + $_SESSION["duelo_puntosPregunta"];
                    $jug1_acertada = 1;
                }else{
                    $jug1_acertada = 0;
                }
                //DECIDIR SI EL JUGADOR 2 ACERTO O NO
                if($this->bot_decidirRespuestaCorrecta($_SESSION["duelo_idDificultad"])){
                    $_SESSION["puntosJug2"] = $_SESSION["puntosJug2"] + $_SESSION["duelo_puntosPregunta"];
                    $jug2_acertada = 0;
                }else{
                    $jug2_acertada = 1;
                }
                $_SESSION["proxima_pregunta"] = true;
                $aux1 = $this->model->preguntaRespondida_duelo($_SESSION["Session_id"],$_SESSION["idDuelo"],$_SESSION["duelo_pregunta_enviada"],$jug1_acertada);
                $aux2 = $this->model->preguntaRespondida_duelo($_SESSION["rival_id"],$_SESSION["idDuelo"],$_SESSION["duelo_pregunta_enviada"],$jug2_acertada);
                Redirect::to("/play/playDuelo");
            }else{
                if($_POST["id_respuesta"] == $_SESSION["respuesta"]){           //CLASICA
                    //SUMAR PUNTOS
                    $_SESSION["puntosPartida"] = $_SESSION["puntosPartida"] + $_SESSION["puntosPregunta"];
                    $_SESSION["proxima_pregunta"] = true;
                    //Ingresar en bbdd que se respondio la pregunta.
                    $aux = $this->model->preguntaRespondida($_SESSION["Session_id"], $_SESSION["idPartida"], $_SESSION["pregunta_enviada"], 1);
                    $aux2 = $this->model->actualizarDificultadPregunta($_SESSION["pregunta_enviada"]);
                    Redirect::to("/play");
                }else{
                    //echo "Respuesta incorrecta!";
                    $aux = $this->model->preguntaRespondida($_SESSION["Session_id"], $_SESSION["idPartida"], $_SESSION["pregunta_enviada"], 0);
                    $this->finalizar_partida();
                }
            }
        }
    }

    public function finalizar_partida()
    {
        $horafinal = date('Y-m-d H:i:s');
        $aux = $this->model->finalizarPartida($_SESSION["idPartida"], $_SESSION["puntosPartida"], $horafinal);
        $data["puntaje"]["puntaje"] = $_SESSION["puntosPartida"];
        unset($_SESSION["partida_iniciada"]);
        unset($_SESSION["pregunta_enviada"]);
        unset($_SESSION["respuesta"]);
        unset($_SESSION["puntosPartida"]);
        unset($_SESSION["puntosPregunta"]);
        unset($_SESSION["dificultadUsuario"]);
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

    public function actualizarDificultad($puntaje, $id_dificultad){
        $newDificultad = $id_dificultad;
        if($puntaje > 300 && $id_dificultad < 2){
            $newDificultad = 2;
        }
        if($puntaje > 600 && $id_dificultad < 3){
            $newDificultad = 3;
        }
        return $newDificultad;
    }

    /*--APARTADO DUELO--*/

    public function playDuelo(){
        if(!isset($_SESSION["Session_id"])){
            //Error en el usuario de sesion - Directo al HOME
            header("Location: /");
        }

        if(isset($_SESSION["proxima_pregunta"])){
            //ESTA LOGICA ESTA RESERVADA CUANDO SE VIENE DE RESPONDER UNA PREGUNTA CORRECTA
            unset($_SESSION["duelo_proxima_pregunta"]); //EVITA LOCURAS CON F5
            unset($_SESSION["duelo_pregunta_enviada"]);
            unset($_SESSION["duelo_respuesta"]);
            unset($_SESSION["duelo_puntosPregunta"]);
            $_SESSION["CantPreguntas"] = $_SESSION["CantPreguntas"] + 1;
            $idDueloIniciada = $_SESSION["idDuelo"];
        } else {
            //NUEVA PARTIDA
            if(isset($_SESSION["duelo_iniciada"])){
                //Apreto "Nueva partida" al tener una partida en progreso o recargo la pagina. Se realiza el proceso de finalizar partida.
                $this->finalizar_duelo();
                return;
            }

            $_SESSION["duelo_iniciada"] = true;
            $horaInicio = date('Y-m-d H:i:s');
            $idDueloIniciada = $this->model->iniciarPartida_duelo($_SESSION["Session_id"],$_SESSION["rival_id"],$horaInicio);
            $_SESSION["idDuelo"] = $idDueloIniciada;
            $_SESSION["puntosJug1"] = 0;
            $_SESSION["puntosJug2"] = 0;
            $_SESSION["CantPreguntas"] = 0;
        }
        if($_SESSION["CantPreguntas"] >= 5){
            //El duelo consta de 5 preguntas cada jugador.
            $this->finalizar_duelo();
            return;
        }
        $data["aux_preguntas"] = $this->model->obtenerPreguntas_duelo($idDueloIniciada);
        if(empty($data["aux_preguntas"]["listaPreguntas"])){
            echo "Se terminaron las preguntas!";
            //TO-DO Logica en caso de que se terminen las preguntas - Por ahora, terminara forzosamente la partida
            $this->finalizar_duelo();
            return;
        }
        $data["aux_pregunta_elegida"] = $this->elegirPregunta($data["aux_preguntas"]["listaPreguntas"]);
        $data["pregunta"] = $this->unificarDatosPregunta($data["aux_pregunta_elegida"]);
        $data["respuesta"] = $this->model->obtenerRespuestas($data["pregunta"]["id_pregunta"]);
        shuffle($data["respuesta"]);

        $_SESSION["duelo_pregunta_enviada"] = $data["pregunta"]["id_pregunta"];
        $_SESSION["duelo_respuesta"] = $this->obtenerIdRespuestaCorrecta($data["respuesta"]);
        $_SESSION["duelo_puntosPregunta"] = $data["pregunta"]["puntos_pregunta"];
        $_SESSION["duelo_idDificultad"] = $data["pregunta"]["id_dificultad"];

        $data["duelo"] = $this->datosVistaDuelo();

        $this->presenter->render("playView", $data);
    }

    public function datosVistaDuelo(){
        $data["jugador1_nombre"] = $_SESSION["Session_id"];
        $data["jugador1_puntaje"] = $_SESSION["puntosJug1"];

        $data["jugador2_nombre"] = $_SESSION["rival_id"];
        $data["jugador2_puntaje"] = $_SESSION["puntosJug2"];

        return $data;
    }

    public function bot_decidirRespuestaCorrecta($dificultad){
        $numeroMagico = rand(0,99999);
        $decision = 0;
        switch($dificultad){
            case 0:
                $decision = $numeroMagico % 10; // Dificultad no definida = 10% chance que el bot acierte
                break;
            case 1:
                $decision = 1;                  // Dificultad Facil = 100% chance que el bot acierte
                break;
            case 2:
                $decision = $numeroMagico % 2;  // Dificultad Media = 50% chance que el bot acierte
                break;
            case 3:
                $decision = $numeroMagico % 4;  // Dificultad Dificil = 25% chance que el bot acierte
                break;
        }
        if($decision == 0){
            return true;
        }else{
            return false;
        }
    }

    public function finalizar_duelo(){
        $horafinal = date('Y-m-d H:i:s');
        $aux = $this->model->finalizarDuelo($_SESSION["idDuelo"], $_SESSION["puntosJug1"], $_SESSION["puntosJug2"], $horafinal);
        $data["duelo"] = $this->datosVistaDuelo();
        unset($_SESSION["rival_id"]);
        unset($_SESSION["idDuelo"]);
        unset($_SESSION["proxima_pregunta"]);
        unset($_SESSION["duelo_iniciada"]);
        unset($_SESSION["duelo_pregunta_enviada"]);
        unset($_SESSION["duelo_respuesta"]);
        unset($_SESSION["puntosJug1"]);
        unset($_SESSION["puntosJug2"]);
        unset($_SESSION["CantPreguntas"]);
        unset($_SESSION["duelo_idDificultad"]);
        $this->presenter->render("partidaTerminadaView", $data);
    }
}