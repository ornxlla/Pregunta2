<?php

class PreguntaModel {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

// agregar una pregunta con una respuesta directamente desde un formulario

    public function agregarPreguntaYRespuesta($preguntaTexto, $respuestaTexto, $idTematica, $idDificultad)
    {
        // Preparar la consulta SQL para insertar la pregunta y obtener su ID
        $stmtPregunta = $this->database->prepare("INSERT INTO preguntas (pregunta_texto, id_tematica, id_dificultad) VALUES (?, ?, ?)");
        $stmtPregunta->bind_param("sii", $preguntaTexto, $idTematica, $idDificultad);
        $stmtPregunta->execute();
        $idPregunta = $stmtPregunta->insert_id;

        // Preparar la consulta SQL para insertar la respuesta asociada
        $stmtRespuesta = $this->database->prepare("INSERT INTO respuestas (id_pregunta, correcta, respuesta_texto) VALUES (?, 1, ?)");
        $stmtRespuesta->bind_param("is", $idPregunta, $respuestaTexto);
        $stmtRespuesta->execute();

        // Verificar si hubo algún error al ejecutar las consultas
        if ($stmtPregunta->error || $stmtRespuesta->error) {
            throw new Exception("Error al insertar pregunta y respuesta en la base de datos.");
        }

        // Cerrar los statements
        $stmtPregunta->close();
        $stmtRespuesta->close();
    }



public function obtenerPreguntasPredefinidas() {
        $stmt = $this->database->query('SELECT id, texto FROM preguntas_predefinidas');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


public function getPreguntasReportadas()
    {
        $query = "SELECT * FROM preguntas_reportadas";

        $result = $this->database->query($query);

        // Verificar si se encontraron resultados
        if (is_object($result) && $result->num_rows > 0) {
            // Crear un array para almacenar las preguntas reportadas
            $preguntasReportadas = [];

            // Recorrer los resultados y almacenarlos en el array
            while ($row = $result->fetch_assoc()) {
                $preguntasReportadas[] = $row;
            }

            // Devolver el array de preguntas reportadas
            return $preguntasReportadas;
        } else {
            return [];
        }
    }


    public function getPreguntasSugeridas()
    {
        $query = "SELECT * FROM preguntas_sugeridas";

        $result = $this->database->query($query);

        if ($result->num_rows > 0) {
            // crea un array para almacenar las preguntas sugeridas
            $preguntasSugeridas = [];

            // recorre los resultados y almacenarlos en el array
            while ($row = $result->fetch_assoc()) {
                $preguntasSugeridas[] = $row;
            }
            //  array de preguntas sugeridas
            return $preguntasSugeridas;
        } else {
            return [];
        }
    }

    /*
    public function agregarPregunta($pregunta, $respuesta, $tematica)
    {

        $query = "INSERT INTO preguntas (pregunta, respuesta, tematica) VALUES (?, ?, ?)";

        $statement = $this->database->prepare($query);

        $statement->bind_param("sss", $pregunta, $respuesta, $tematica);

        $statement->execute();

        if ($statement->affected_rows > 0) {
            // La pregunta se agregó
            return true;
        } else {
            // Hubo un error al agregar la pregunta
            return false;
        }
    }*/

    public function eliminarPregunta($preguntaID)
    {
        //  eliminar la pregunta utilizando el ID
        $query = "DELETE FROM preguntas WHERE pregunta_id = ?";

        $statement = $this->database->prepare($query);

        $statement->bind_param("i", $preguntaID);

        $statement->execute();

        $statement->close();
    }



    public function modificarPregunta($idPregunta, $pregunta, $respuesta, $tematica)
    {
        $query = "UPDATE preguntas SET pregunta = ?, respuesta = ?, tematica = ? WHERE id = ?";

        $statement = $this->database->prepare($query);

        $statement->bind_param("sssi", $pregunta, $respuesta, $tematica, $idPregunta);
        $result = $statement->execute();

        if ($result) {
            return true; // La pregunta se modificó
        } else {
            return false; // Hubo un error al modificar la pregunta
        }
    }






}

?>


