<?php

class PreguntaModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }


    public function reportarPregunta($idPregunta)
    {
        $query = "INSERT INTO preguntas_reportadas (id_pregunta) VALUES (?)";
        $statement = $this->database->prepare($query);

        if (!$statement) {
            throw new \Exception("Preparación de la consulta fallida: " . $this->database->error);
        }

        // Vincular parámetros y ejecutar la consulta
        $statement->bind_param("i", $idPregunta);
        $statement->execute();

        if ($statement->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function obtenerPreguntasGenerales()
    {
        $query = "SELECT id_pregunta, texto FROM preguntas_listado WHERE aprobado = 1";
        $result = $this->database->query($query);

        if ($result) {
            error_log("Query executed successfully, number of rows: " . count($result));
        } else {
            error_log("Query failed to execute.");
        }

        return $result;
    }

    // paso 1
    public function obtenerPreguntasReportadas()
    {
        $query = "SELECT preguntas_reportadas.id_pregunta, preguntas_listado.texto 
              FROM preguntas_reportadas 
              JOIN preguntas_listado ON preguntas_reportadas.id_pregunta = preguntas_listado.id_pregunta";
        return $this->database->query($query);
    }

    public function aprobarPregunta($idPregunta)
    {
        // Eliminar la entrada correspondiente en preguntas_reportadas si existe
        $query = "DELETE FROM preguntas_reportadas WHERE id_pregunta = ?";
        $statement = $this->database->prepare($query);
        $statement->bind_param("i", $idPregunta);
        $statement->execute();
        $statement->close();
    }

    public function darDeBajaPregunta($idPregunta)
    {
        // Pone la pregunta como no aprobado
        $query = "UPDATE preguntas_listado SET aprobado = 0 WHERE id_pregunta = ? ";
        $statement = $this->database->prepare($query);
        $statement->bind_param("i", $idPregunta);
        $statement->execute();
        $statement->close();
    }

    public function obtenerDificultades()
    {
        $query = "SELECT id, nombre FROM dificultad";
        return $this->database->query($query);
    }

    public function obtenerTematicas()
    {
        $query = "SELECT id_tematica, nombre FROM tematicas";
        return $this->database->query($query);
    }


    public function insertarPreguntaSugerida($pregunta_texto, $id_dificultad, $id_tematica)
    {
        $query = "INSERT INTO preguntas_listado (texto, id_dificultad, id_tematica, aprobado, es_sugerida)
              VALUES (?, ?, ?, 0, 1)";

        $stmt = $this->database->prepare($query);
        $stmt->bind_param("sii", $pregunta_texto, $id_dificultad, $id_tematica);

        if ($stmt->execute()) {
            return $this->database->last_insert();
        } else {
            return false;
        }
    }

    public function insertarRespuesta($id_pregunta, $respuesta_texto, $es_correcta)
    {
        $query = "INSERT INTO respuesta_listado (id_pregunta, texto, correcta) VALUES (?, ?, ?)";

        $stmt = $this->database->prepare($query);
        $stmt->bind_param("isi", $id_pregunta, $respuesta_texto, $es_correcta);

        return $stmt->execute();
    }

    public function obtenerPreguntasSugeridas()
    {
        $query = "SELECT id_pregunta, texto FROM preguntas_listado WHERE es_sugerida = 1";
        return $this->database->query($query);
    }

    public function aprobarPreguntaSugerida($idPregunta)
    {
        $query = "UPDATE preguntas_listado SET aprobado = 1, es_sugerida = 0 WHERE id_pregunta = ?";
        $stmt = $this->database->prepare($query);
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();
        $stmt->close();
    }

    public function obtenerPreguntaPorId($idPregunta)
    {
        $query = "SELECT * FROM preguntas_listado WHERE id_pregunta = ? AND aprobado = 1";
        $stmt = $this->database->prepare($query);
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function obtenerRespuestasPorIdPregunta($idPregunta)
    {
        $query = "SELECT * FROM respuesta_listado WHERE id_pregunta = ?";
        $stmt = $this->database->prepare($query);
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();
        $result = $stmt->get_result();
        $respuestas = [];
        while ($row = $result->fetch_assoc()) {
            $respuestas[] = $row;
        }
        return $respuestas;
    }

    public function actualizarPregunta($idPregunta, $preguntaTexto, $idTematica, $idDificultad)
    {
        $query = "UPDATE preguntas_listado SET texto = ?, id_tematica = ?, id_dificultad = ? WHERE id_pregunta = ?";
        $stmt = $this->database->prepare($query);
        $stmt->bind_param("siii", $preguntaTexto, $idTematica, $idDificultad, $idPregunta);
        return $stmt->execute();
    }

    public function actualizarRespuesta($idRespuesta, $respuestaTexto, $esCorrecta)
    {
        $query = "UPDATE respuesta_listado SET texto=?, correcta=? WHERE id_respuesta=?";
        $stmt = $this->database->prepare($query);
        $stmt->bind_param("sii", $respuestaTexto, $esCorrecta, $idRespuesta);
        return $stmt->execute();
    }

    public function listarPreguntasSugeridas()
    {
        $query = "SELECT pl.id_pregunta, pl.texto AS pregunta, rl.id_respuesta, rl.texto AS respuesta
              FROM preguntas_listado pl
              LEFT JOIN respuesta_listado rl ON pl.id_pregunta = rl.id_pregunta
              WHERE pl.es_sugerida = 1";
        $result = $this->database->query($query);

        // Verifica si la consulta falló
        if ($result === false) {
            die('Error en la consulta');
        }

        // Organiza los resultados en un formato adecuado para la vista
        $preguntas = [];
        foreach ($result as $row) {
            $idPregunta = $row['id_pregunta'];
            if (!isset($preguntas[$idPregunta])) {
                $preguntas[$idPregunta] = [
                    'id_pregunta' => $idPregunta,
                    'pregunta' => $row['pregunta'],
                    'respuestas' => []
                ];
            }

            // Agrega la respuesta solo si existe
            if ($row['id_respuesta']) {
                $preguntas[$idPregunta]['respuestas'][] = [
                    'id_respuesta' => $row['id_respuesta'],
                    'respuesta' => $row['respuesta']
                ];
            }
        }
        return array_values($preguntas);
    }

}

?>


