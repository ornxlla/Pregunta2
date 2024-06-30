<?php

class PlayModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function iniciarPartida_clasico($idUsuario, $horaInicio)
    {
        $sql = "insert into partida_clasica_general (id_jugador, hora_inicio) values (?, ?)";
        $stmt = $this->database->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("is", $idUsuario, $horaInicio);
            $stmt->execute();
            return $stmt->insert_id;
        } else {
            return null;
        }
    }

    public function obtenerDificultadUsuario($idUsuario){
        $sql = "SELECT COUNT(id) as 'veces_respondido', SUM(acertado) as 'veces_acertado'
                FROM partida_clasica_respuestas 
                WHERE id_jugador = ?";

        $stmt = $this->database->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $idUsuario);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = [];
            if ($result) {
                while($row = $result->fetch_assoc()){
                    if($row["veces_respondido"] <= 10){          //No contesto lo suficiente para salir de MODO FACIL
                        $data = 1;
                    }else{
                        if($row["veces_acertado"] <= 10){        //No contesto lo suficiente para salir de MODO FACIL
                            $data = 1;
                        }else{
                            //$calculo = ($row["veces_respondido"] * $row["veces_acertado"]) / 100;
                            $calculo = $row["veces_acertado"] / $row["veces_respondido"];
                            if($calculo > 0.70){                //Promedio de +70% Acertado = Bastante habilidoso -> Mandarle una dificil
                                $data = 3;
                            } elseif ($calculo < 0.30){         //Promedio de -30% Acertado = No tan bueno -> Mandarle una facil
                                $data = 1;
                            }else{                               //Entre 30% y 70% -> Intermedio
                                $data = 2;
                            }
                        }
                    }
                }
                return $data;
            }
        }
    }

    public function obtenerPreguntas_clasico($idPartida, $idUsuario, $dificultad)
    {
        $data["listaPreguntas"] = $this->obtenerListaPreguntas($idPartida, $idUsuario);

        return $data;
    }

    public function obtenerListaPreguntas($idPartida, $idUsuario){
        $sql = "SELECT preg.id_pregunta, preg.texto, tem.nombre AS tematica
                 FROM preguntas_listado AS preg
                 INNER JOIN tematicas as tem
                 ON preg.id_tematica = tem.id_tematica
                  WHERE preg.aprobado = 1
                    AND !EXISTS (
                     SELECT part.*
                     FROM partida_clasica_respuestas as part
                     WHERE part.id_jugador = ?
                     AND part.id_partida = ?
                     AND part.id_pregunta = preg.id_pregunta
                 )";
        $stmt = $this->database->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ii", $idUsuario, $idPartida );
            $stmt->execute();
            $result = $stmt->get_result();
            $data = [];
            if ($result) {
                $i = 0;
                while($row = $result->fetch_assoc()){
                    $data[$i] = $row;
                    $i = $i + 1;
                }
                return $data;
            }
        }
        return null;
    }

    public function obtenerDificultadPreguntas($id_pregunta){
        $sql = "SELECT COUNT(id) as 'veces_llamado', SUM(acertado) as 'veces_acertado' 
                FROM partida_clasica_respuestas 
                WHERE id_pregunta = ?";
        $stmt = $this->database->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $id_pregunta);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = [];
            if ($result) {
                $i = 0;
                while($row = $result->fetch_assoc()){
                    $data[$i] = $this->calcularDificultadPregunta($row);
                    $i = $i + 1;
                }
                return $data;
            }
        }
    }

    public function actualizarDificultadPregunta($id_pregunta){
        $datosAux = $this->obtenerDificultadPreguntas($id_pregunta);
        $sql = "UPDATE preguntas_listado SET id_dificultad = ? WHERE id_pregunta = ?";
        $stmt = $this->database->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ii", $datosAux[0]["dificultad"],$id_pregunta);
            return $stmt->execute();
        } else {
            return null;
        }
    }

    public function calcularDificultadPregunta($estadisticas){
        $data = [];
        //Si la pregunta es nueva (0 llamadas), se considerara como "Indefinido" y se dara 100 puntos
        if($estadisticas["veces_llamado"] == 0){
            $data["dificultad"] = 0;
            $data["puntos_correcto"] = 100;
            $data["texto_dificultad"] = "No definido";
        }elseif($estadisticas["veces_llamado"] < 7 && $estadisticas["veces_acertado"] == 0){
            //Si fue llamado menos de 7 veces y no tiene respuestas acertadas, se considerara Medio
            $data["dificultad"] = 2;
            $data["puntos_correcto"] = 50;
            $data["texto_dificultad"] = "Medio";
        }elseif($estadisticas["veces_llamado"] >= 7 && $estadisticas["veces_acertado"] == 0){
            //Si fue llamado mas o igual a 7 veces y no tiene respuestas acertadas, se considerara dificil
            $data["dificultad"] = 3;
            $data["puntos_correcto"] = 100;
            $data["texto_dificultad"] = "Dificil";
        }else {
            //$estadistica = ($estadisticas["veces_llamado"] * $estadisticas["veces_acertado"]) / 100;
            $estadistica = $estadisticas["veces_acertado"] / $estadisticas["veces_llamado"];
            if ($estadistica > 0.70) {    //Mas del 70% acertado = Facil
                $data["dificultad"] = 1;
                $data["puntos_correcto"] = 25;
                $data["texto_dificultad"] = "Facil";
            } elseif ($estadistica < 0.30) {   //Menos del 30% acertado = dificil
                $data["dificultad"] = 3;
                $data["puntos_correcto"] = 100;
                $data["texto_dificultad"] = "Dificil";
            } else {  //demas = medio
                $data["dificultad"] = 2;
                $data["puntos_correcto"] = 50;
                $data["texto_dificultad"] = "Medio";
            }
        }
        return $data;
    }

    public function obtenerRespuestas($idPregunta)
    {
        $sql = "SELECT * FROM respuesta_listado WHERE id_pregunta = $idPregunta";

        return $this->database->query($sql);
    }

    public function preguntaRespondida($id_jugador, $id_partida, $id_pregunta, $acertada){
        $sql = "insert into partida_clasica_respuestas (id_jugador, id_partida, id_pregunta, acertado) values (?, ?, ?, ?)";
        $stmt = $this->database->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("iiii", $id_jugador, $id_partida, $id_pregunta, $acertada);
            $stmt->execute();
            return $stmt->insert_id;
        } else {
            return null;
        }
    }

    public function finalizarPartida($id_partida, $puntos, $hora_final){
        $sql = "UPDATE partida_clasica_general SET puntos = ?, hora_final = ? WHERE id = ?";
        $stmt = $this->database->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("isi", $puntos,  $hora_final, $id_partida);
            return $stmt->execute();
        } else {
            return null;
        }
    }
}