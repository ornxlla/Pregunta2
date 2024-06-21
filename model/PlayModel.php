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

    public function obtenerPreguntas_clasico($idPartida, $idUsuario)
    {
        $data["listaPreguntas"] = $this->obtenerListaPreguntas($idPartida, $idUsuario);
        $data["dificultadPreguntas"] = $this->obtenerDificultadPreguntas($idPartida, $idUsuario);

        return $data;
    }

    public function obtenerListaPreguntas($idPartida, $idUsuario){
        $sql = "SELECT preg.id_pregunta, preg.texto, tem.nombre AS tematica
                 FROM preguntas_listado AS preg
                 INNER JOIN tematicas as tem
                 ON preg.id_tematica = tem.id_tematica
                 WHERE !EXISTS(
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

    public function obtenerDificultadPreguntas($idPartida, $idUsuario){
        /*
        $sql = "SELECT est.* 
                FROM pregunta_estadisticas AS est
                WHERE !EXISTS(
                     SELECT part.*
                     FROM partida_clasica_respuestas as part
                     WHERE part.id_jugador = ?
                     AND part.id_partida = ?
                     AND part.id_pregunta = est.id_pregunta
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
                    $data[$i] = $this->calcularDificultadPregunta($row);
                    $i = $i + 1;
                }
                return $data;
            }
        }
        return null;*/
        $sql = "SELECT id_pregunta, COUNT(id) as 'veces_llamado', SUM(acertado) as 'veces_acertado' 
                FROM partida_clasica_respuestas 
                GROUP BY id_pregunta";
        $stmt = $this->database->prepare($sql);
        if ($stmt) {
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

    public function calcularDificultadPregunta($estadisticas){
        $data = [];
        $data["id_pregunta"] = $estadisticas["id_pregunta"];
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
            $estadistica = ($estadisticas["veces_llamado"] * $estadisticas["veces_acertado"]) / 100;
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


    /*---------------------------*/
    public function iniciarPartida($idUsuario, $horaInicio)
    {
        /*
        $sql = "insert into partida (id_jugador_1, hora_inicio) values ($idUsuario, '$horaInicio')";
        echo $sql;
        $resultado = $this->database->query($sql);
        echo $resultado;
        return $this->database->last_insert();
        */
        $sql = "insert into partida (id_jugador_1, hora_inicio) values (?, ?)";
        $stmt = $this->database->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("is", $idUsuario, $horaInicio);
            $stmt->execute();
            return $stmt->insert_id;
        } else {
            return null;
        }
    }

    public function obtenerUnaPregunta($idPartida, $idUsuario)
    {
        $cantidadDePartidas = intval($this->obtenerCantidadDePartidasDelJugador($idUsuario));
        $cantidadRespuestasCorrectas = intval($this->obtenerCantidadDeRespuestasCorrectasDelJugador($idUsuario));


        if ($cantidadDePartidas < 10 || $cantidadRespuestasCorrectas < 30) {

            $sql = $this->obtenerPreguntaSinDificultad($idPartida);

        } else {

            $dificultadUsuario = intval($this->obtenerDificultadDelUsuario($idUsuario));
            $sql = $this->obtenerPreguntasSegunDificultadDelUsuario($idPartida, $dificultadUsuario);

        }
        //echo $sql;
        $resultado = $this->database->query($sql);

        if (empty($resultado)) {
            $queryEmergencia = $this->obtenerPreguntaSinDificultad($idPartida);
            $resultado = $this->database->query($queryEmergencia);
        }

        return $resultado[0];
    }

    public function guardarRespuestaPorPartida($idPartida, $respuesta)
    {
        $sql = "insert into respuestas_partida (id_partida, id_respuesta) values (?, ?)";

        $stmt = $this->database->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ii", $idPartida, $respuesta);
            $stmt->execute();
            return $stmt->insert_id;
        }else{
            return null;
        }
    }

    public function guardarPreguntaPorPartida($idPartida, $pregunta)
    {
        /*
        $sql = "insert into preguntas_partida (id_partida, id_pregunta) values ($idPartida, $pregunta)";

        $this->database->query($sql);
        */
        $sql = "insert into preguntas_partida (id_partida, id_pregunta) values (?, ?)";
        $stmt = $this->database->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("is", $idPartida, $pregunta);
            $stmt->execute();
            return $stmt->insert_id;
        }else{
            return null;
        }
    }

    public function aumentarContadorApariciones($idPregunta)
    {
        /*
        $sql = "update preguntas set apariciones = apariciones + 1 where id = $idPregunta";

        $this->database->query($sql);
        */
        $sql = "update preguntas set apariciones = apariciones + 1 where id_pregunta = ?";
        $stmt = $this->database->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $idPregunta);
            return $stmt->execute();
        }
    }

    public function aumentarCantidadDeAciertosDeLaPregunta($idPregunta)
    {
        $sql = "update preguntas set contador_respuestas_correctas = contador_respuestas_correctas + 1 where id_pregunta = ?";
        $stmt = $this->database->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $idPregunta);
            return $stmt->execute();
        } else {
            return null;
        }
    }
    public function sumarUnPunto($idPartida, $idUsuario)
    {
        $sql = "SELECT puntos_jugador_1 FROM partida WHERE id = ? AND id_jugador_1 = ?";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("ii", $idPartida, $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $numRows = $result->num_rows;
        $stmt->close();

        if ($numRows > 0) {
            $row = $result->fetch_assoc();
            $puntosJugador = $row['puntos_jugador_1'];
            if ($puntosJugador === NULL) {
                $puntosJugador = 0;
            }
            $puntosJugador++;
            $sql = "UPDATE partida SET puntos_jugador_1 = ? WHERE id = ? AND id_jugador_1 = ?";
            $stmt = $this->database->prepare($sql);
            $stmt->bind_param("iii", $puntosJugador, $idPartida, $idUsuario);
            $stmt->execute();
            $stmt->close();
        }
    }
    public function obtenerUnaPreguntaPorId($filter)
    {
        $sql = "select preguntas.*, tematicas.nombre as tematicas from preguntas
                JOIN tematicas on preguntas.tematicas = tematicas.id_tematica
                where preguntas.id_pregunta = $filter
                order by rand() limit 1";

        $resultado = $this->database->query($sql);

        return $resultado[0];
    }

    public function obtenerRespuestasDeUnaPregunta($idPregunta)
    {
        $sql = "SELECT * FROM respuesta WHERE id_pregunta = $idPregunta";

        return $this->database->query($sql);
    }

    public function obtenerHistorialPartida($idFilter)
    {
        $sql = "SELECT * from partida where id_jugador_1 = $idFilter";

        return $this->database->query($sql);
    }

    private function obtenerCantidadDePartidasDelJugador($idUsuario)
    {
        $sql = "select count(*) as cantidad_partidas from partida where id_jugador_1 = $idUsuario";

        $resultado = $this->database->query($sql);

        return $resultado[0]["cantidad_partidas"];
    }

    private function obtenerCantidadDeRespuestasCorrectasDelJugador($idUsuario)
    {
        $sql = "select 
                    count(*) as cantidad_respuestas_correctas
                from 
                    respuestas_partida
                JOIN
                    partida
                ON
                    respuestas_partida.id_partida = partida.id
                join
                    respuesta
                ON
                    respuestas_partida.id_respuesta = respuesta.id_respuesta
                where 
                    partida.id_jugador_1 = $idUsuario
                and 
                    respuesta.correcta = 1";

        $resultado = $this->database->query($sql);

        return $resultado[0]["cantidad_respuestas_correctas"];
    }

    public function actualizarPartida($idPartida)
    {
        $horaFinal = date('Y-m-d H:i:s');

        $sql = "update partida set hora_final = '$horaFinal' where id = $idPartida";

        $this->database->query($sql);
    }

    private function obtenerPreguntaSinDificultad($idPartida)
    {
        return "SELECT 
                    preguntas.*, tematicas.nombre AS tematicas
                FROM 
                    preguntas
                JOIN 
                    tematicas 
                ON 
                    preguntas.id_tematica = tematicas.id_tematica
                WHERE 
                    preguntas.id_pregunta not in (
                                        select 
                                            id_pregunta 
                                        from 
                                            preguntas_partida 
                                        where
                                            id_partida = $idPartida)
                AND 
                    preguntas.estado = 1
                ORDER BY 
                    RAND() LIMIT 1";
    }

    private function obtenerPreguntasSegunDificultadDelUsuario($idPartida, $id_dificultad)
    {
        return "SELECT 
                    preguntas.*, tematicas.nombre AS tematicas 
                FROM 
                    preguntas
                JOIN 
                    tematicas 
                ON 
                    preguntas.id_categoria = tematicas.id_tematica
                WHERE 
                    preguntas.id_pregunta not in (
                                        select 
                                            id_pregunta 
                                        from 
                                            preguntas_partida 
                                        where
                                            id_partida = $idPartida)
                AND
                    preguntas.id_dificultad = $id_dificultad
                AND
                    preguntas.estado = 1
                ORDER BY 
                    RAND() LIMIT 1";
    }

    private function obtenerDificultadDelUsuario($idUsuario)
    {
        $sql = "select id_dificultad from usuario where id = $idUsuario";

        $resultado = $this->database->query($sql);

        return $resultado[0]["id_dificultad"];
    }

    public function calcularDificultadDelUsuario($idUsuario)
    {
        $cantidadRespuestasCorrectas = intval($this->obtenerCantidadAciertosDelUsuario($idUsuario));
        $cantidadIntentos = intval($this->obtenerCantidadDeIntentosDelUsuario($idUsuario));

        if ($cantidadIntentos > 0) {

            $ratio = ($cantidadRespuestasCorrectas / $cantidadIntentos) * 100;

            $sql = "UPDATE usuario
                SET id_dificultad =
                  CASE
                    WHEN $ratio <= 20 THEN 1
                    WHEN $ratio > 20 AND $ratio <= 50 THEN 2
                    WHEN $ratio > 50 AND $ratio <= 85 THEN 3
                    WHEN $ratio > 85 THEN 4
                  END
                WHERE
                    id = $idUsuario";

            $this->database->query($sql);

        }
    }

    private function obtenerCantidadAciertosDelUsuario($idUsuario)
    {
        $sql = "select
                    count(*) as cant_aciertos
                from
                    respuestas_partida
                join
                    respuesta on respuestas_partida.id_respuesta = respuesta.id_respuesta
                join 
                    partida on respuestas_partida.id_partida = partida.id
                where 
                    partida.id_jugador_1 = $idUsuario
                and 
                    respuesta.correcta = 1";

        $resultado = $this->database->query($sql);

        return $resultado[0]["cant_aciertos"];
    }

    private function obtenerCantidadDeIntentosDelUsuario($idUsuario)
    {
        $sql = "select
                    count(*) as cant_intentos
                from
                    respuestas_partida
                join
                    respuesta on respuestas_partida.id_respuesta = respuesta.id_respuesta
                join 
                    partida on respuestas_partida.id_partida = partida.id
                where 
                    partida.id_jugador_1 = $idUsuario";

        $resultado = $this->database->query($sql);

        return $resultado[0]["cant_intentos"];
    }

    public function actualizarDificultadPreguntas()
    {

        $sql = "UPDATE preguntas
                SET id_dificultad = 
                    CASE
                        WHEN (contador_respuestas_correctas / apariciones) > 0.7 THEN 1
                        WHEN (contador_respuestas_correctas / apariciones) > 0.5 THEN 2
                        WHEN (contador_respuestas_correctas / apariciones) > 0.3 THEN 3
                        ELSE 4
                    END
                WHERE apariciones > 10";

        $this->database->query($sql);
    }
}