<?php

class PreguntaModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

// paso 1
    public function obtenerPreguntasReportadas()
    {
        $query = "SELECT id_pregunta, texto FROM preguntas_listado WHERE reportada = 1";
        return $this->database->query($query);
    }

//paso 2 aprobar pregunta

    public function aprobarPregunta($idPregunta)
    {
        $query = "UPDATE preguntas_listado SET reportada = 0 WHERE id_pregunta = '$idPregunta'";
        $this->database->execute($query);
    }

//paso 3 eliminar pregunta

    public function darDeBajaPregunta($idPregunta)
    {
        $query = "DELETE FROM preguntas_listado WHERE id_pregunta = '$idPregunta'";
        $this->database->execute($query);
    }


// PREGUNTAS SUGERIDAS : // el editor puede aprobar las preguntas sugeridas por usuarios

    public function obtenerPreguntasSugeridas()
    {
        $query = "SELECT id_pregunta, texto FROM preguntas_listado WHERE es_sugerida = 1";
        return $this->database->query($query);
    }


    public function aprobarPreguntaSugerida($idPregunta)
    {
        $query = "UPDATE preguntas_listado SET es_sugerida = 0 WHERE id_pregunta = $idPregunta";
        $this->database->execute($query);
    }


// el editor podrá ver los cambios de sus funciones en el listado general de preguntas, es decir que trae las preguntas que no son sugeridas ni reportadas  (porque cuando las aceptó dichos campos pasaron a estar en false)

    public function obtenerPreguntasGenerales()
    {
        $query = "SELECT id_pregunta, texto FROM preguntas_listado WHERE es_sugerida = 0 AND reportada = 0";
        return $this->database->query($query);
    }

// consigna: Debe existir un tipo de usuario editor, que le permite dar de alta, baja y modificar las preguntas:

// 1ER PASO: CREAR PREGUNTA Y SE AGREGA AL LISTADO  ok

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

// ahora hay que insertar esa pregunta en la bbdd OK

    public function insertarPregunta($pregunta_texto, $id_dificultad, $id_tematica) {

        $query = "INSERT INTO preguntas_listado (texto, id_tematica, id_dificultad, reportada, es_sugerida) 
                  VALUES (?, ?, ?, 0, 1)";

        $stmt = $this->database->prepare($query);

        $stmt->bind_param("sii", $pregunta_texto, $id_dificultad, $id_tematica);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

// eliminar pregunta del listado ok

    public function eliminarPregunta($idPregunta)
    {
        $query = "DELETE FROM preguntas_listado WHERE id_pregunta = '$idPregunta'";
        $this->database->execute($query);
    }


    //  crear pregunta sugerida OK *********


    public function insertarPreguntaSugerida($pregunta_texto, $id_dificultad, $id_tematica) {
        $query = "INSERT INTO preguntas (texto, id_tematica, id_dificultad, reportada, es_sugerida) 
              VALUES (?, ?, ?,  0, 1)";

        $stmt = $this->database->prepare($query);
        $stmt->bind_param("sii", $pregunta_texto, $id_dificultad, $id_tematica);

        if ($stmt->execute()) {
            return $stmt->insert_id;
        } else {
            return false;
        }
    }

    public function insertarRespuesta($id_pregunta, $respuesta_texto, $es_correcta) {
        $query = "INSERT INTO respuesta_listado (id_pregunta, texto, correcta) VALUES (?, ?, ?)";

        $stmt = $this->database->prepare($query);
        $stmt->bind_param("isi", $id_pregunta, $respuesta_texto, $es_correcta);

        return $stmt->execute();
    }

// MODIFICAR PREGUNTA Y RESPUESTA ********

    public function obtenerPreguntaPorId($idPregunta) {
        $query = "SELECT * FROM preguntas_listado WHERE id_pregunta = ?";
        $stmt = $this->database->prepare($query);
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function obtenerRespuestasPorIdPregunta($idPregunta) {
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


//metodo a revisar

    public function actualizarPregunta($idPregunta, $preguntaTexto, $idTematica, $idDificultad)
    {
        $query = "UPDATE preguntas_listado SET texto = ?, id_tematica = ?, id_dificultad = ? WHERE id_pregunta = ?";
        $stmt = $this->database->prepare($query);
        $stmt->bind_param("siii", $preguntaTexto, $idTematica, $idDificultad, $idPregunta);
        return $stmt->execute();
    }

//** ver porque actualiza las preguntas pero no las respuestas  */

    public function actualizarRespuestas($respuestas, $idPregunta) {
        foreach ($respuestas as $respuesta) {
            $respuestaTexto = $respuesta['respuesta_texto'];
            $idRespuesta = $respuesta['id_respuesta'];
            $esCorrecta = ($respuesta['id_respuesta'] == $_POST['respuesta_correcta']) ? 1 : 0;

            // Preparar la consulta para actualizar respuestas
            $query = "UPDATE respuesta_listado SET texto=?, correcta=? WHERE id_respuesta=?";
            $stmt = $this->db->prepare($query);

            if ($stmt === false) {
                // Manejo de error si prepare() falla
                die('Error de preparación de consulta: ' . $this->db->error);
            }

            // Vincular parámetros y ejecutar consulta
            $stmt->bind_param("sii", $respuestaTexto, $esCorrecta, $idRespuesta);
            $stmt->execute();

            if ($stmt->error) {
                // Manejo de error si execute() falla
                die('Error al ejecutar consulta: ' . $stmt->error);
            }

            // Cerrar declaración
            $stmt->close();
        }
    }

}

?>


