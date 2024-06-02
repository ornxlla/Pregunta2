<?php

class playModel

{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getPreguntaRandom($dificultad)
    {
        if ($dificultad != NULL) {
            $sql = "SELECT * FROM Preguntas WHERE Utilizada = '0' AND Dificultad = '$dificultad' ORDER BY RAND() LIMIT 1";
            $pregunta = $this->database->query($sql);

            if (!empty($pregunta)) {

                return $pregunta[0];
            }else{
                throw new Exception("No hay mas preguntas para tu nivel.");
            }
        } else {
            $sql = "SELECT * FROM Preguntas WHERE Utilizada = '0' ORDER BY RAND() LIMIT 1";
            $pregunta = $this->database->query($sql);

            if (!empty($pregunta)) {

                return $pregunta[0];
            }else{
                throw new Exception("No hay mas preguntas disponibles.");
            }
        }

    }

    public function getRespuestas($pregunta)
    {
        $sql = "SELECT * FROM Respuesta WHERE Pregunta_ID = $pregunta ORDER BY RAND()";
        return $this->database->query($sql);
    }

    public function marcarPreguntaUtilizada($preguntaID)
    {
        $sql = "UPDATE Preguntas SET Utilizada = 1 WHERE Pregunta_ID = $preguntaID";
        $this->database->query($sql);
    }

    public function validarRespuesta($respuestaID)
    {
        $sql = "SELECT * FROM respuesta WHERE Respuesta_ID = $respuestaID AND Correcta = 1";
        $respuesta = $this->database->query($sql);

        if (isset($respuesta[0])) {
            return $respuesta[0];
        } else {
            return false;
        }
    }

    public function getPuntajeActual($actualUser)
    {
        $sql = "SELECT puntuacion_actual FROM usuarios WHERE id = $actualUser";
        $puntaje = $this->database->query($sql);
        return $puntaje[0]['puntuacion_actual'];
    }

    public function guardarPuntaje($userID, $puntaje)
    {
        $sql = "UPDATE usuarios SET puntuacion_actual = $puntaje WHERE id = $userID";
        return $this->database->update($sql);
    }

    public function getPuntajeMasAlto($userID)
    {
        $sql = "SELECT puntuacion_masalta FROM usuarios WHERE id = $userID";
        $result = $this->database->query($sql);

        if (!empty($result)) {
            return (int)$result[0]['puntuacion_masalta'];
        } else {
            return 0;
        }
    }

    public function actualizarPuntajeMasAlto($userID, $puntaje)
    {
        $sql = "UPDATE usuarios SET puntuacion_masalta = $puntaje WHERE id = $userID";
        return $this->database->update($sql);
    }

    public function marcarPreguntasUtilizadas()
    {
        $sql = "UPDATE Preguntas SET Utilizada = 0 WHERE Utilizada = 1";
        $this->database->query($sql);
    }

    public function reportQuestion($preguntaID)
    {
        $query = "INSERT INTO preguntas_reportadas (id_pregunta_reportada) VALUES($preguntaID);";
        return $this->database->insert($query);
    }

    public function getContadorRespuestasCorrectas($preguntaID)
    {
        $sql = "SELECT contador_respuestas_correctas FROM preguntas WHERE pregunta_ID = $preguntaID";
        $result = $this->database->query($sql);

        if (!empty($result)) {
            return $result[0]['contador_respuestas_correctas'];
        } else {
            return null;
        }
    }

    public function getContadorRespuestasCorrectasDelUsuario($idUsuario)
    {
        $sql = "SELECT contador_respuestas_correctas FROM usuarios WHERE id = $idUsuario";
        $result = $this->database->query($sql);

        if (!empty($result)) {
            return $result[0]['contador_respuestas_correctas'];
        } else {
            return null;
        }
    }



    public function incrementarContadorRespuestasCorrectas($idUsuario, $preguntaID)
    {
        $contadorRespuestasCorrectasPregunta = $this->getContadorRespuestasCorrectas($preguntaID);
        $contadorRespuestasCorrectasUsuario = $this->getContadorRespuestasCorrectasDelUsuario($idUsuario);

        // Incrementa el contador de la pregunta
        $this->database->update("UPDATE preguntas SET contador_respuestas_correctas = $contadorRespuestasCorrectasPregunta + 1 WHERE Pregunta_ID = $preguntaID");

        // Incrementa el contador del usuario
        $this->database->update("UPDATE usuarios SET contador_respuestas_correctas = $contadorRespuestasCorrectasUsuario + 1 WHERE id = $idUsuario");
    }

    public function getContadorRespuestasIncorrectas($preguntaID)
    {
        $sql = "SELECT contador_respuestas_incorrectas FROM preguntas WHERE pregunta_ID = $preguntaID";
        $result = $this->database->query($sql);

        if (!empty($result)) {
            return $result[0]['contador_respuestas_incorrectas'];
        } else {
            return null;
        }
    }

    public function getContadorRespuestasIncorrectasDelUsuario($idUsuario)
    {
        $sql = "SELECT contador_respuestas_incorrectas FROM usuarios WHERE id = $idUsuario";
        $result = $this->database->query($sql);

        if (!empty($result)) {
            return $result[0]['contador_respuestas_incorrectas'];
        } else {
            return null;
        }
    }

    public function incrementarContadorRespuestasIncorrectas($idUsuario, $preguntaID)
    {
        // Incrementa el contador de la pregunta
        $this->database->update("UPDATE preguntas SET contador_respuestas_incorrectas = contador_respuestas_incorrectas + 1 WHERE Pregunta_ID = $preguntaID");

        // Incrementa el contador del usuario
        $this->database->update("UPDATE usuarios SET contador_respuestas_incorrectas = contador_respuestas_incorrectas + 1 WHERE id = $idUsuario");
    }

    public function calcularDificultadPregunta($preguntaID)
    {
        $contadorRespuestasCorrectas = $this->getContadorRespuestasCorrectas($preguntaID);
        $contadorRespuestasIncorrectas = $this->getContadorRespuestasIncorrectas($preguntaID);

        if (($contadorRespuestasCorrectas + $contadorRespuestasIncorrectas) > 10) {
            $dificultad = $contadorRespuestasCorrectas / ($contadorRespuestasCorrectas + $contadorRespuestasIncorrectas);

            switch ($dificultad) {
                case $dificultad <= 0.3:
                    $this->database->update("UPDATE preguntas SET Dificultad = 'Dificil' WHERE Pregunta_ID = $preguntaID");
                    break;
                case $dificultad <= 0.6:
                    $this->database->update("UPDATE preguntas SET Dificultad = 'Medio' WHERE Pregunta_ID = $preguntaID");
                    break;
                case $dificultad <= 1:
                    $this->database->update("UPDATE preguntas SET Dificultad = 'Facil' WHERE Pregunta_ID = $preguntaID");
                    break;
                default:
                    return null;
                    break;
            }
        }
    }

    public function calcularDificultadUsuario($idUsuario)
    {
        $contadorRespuestasCorrectas = $this->getContadorRespuestasCorrectasDelUsuario($idUsuario);
        $contadorRespuestasIncorrectas = $this->getContadorRespuestasIncorrectasDelUsuario($idUsuario);

        if (($contadorRespuestasCorrectas + $contadorRespuestasIncorrectas) > 10) {
            $dificultad = $contadorRespuestasCorrectas / ($contadorRespuestasCorrectas + $contadorRespuestasIncorrectas);

            switch ($dificultad) {
                case $dificultad <= 0.3:
                    $this->database->update("UPDATE usuarios SET nivel = 'Principiante' WHERE id = $idUsuario");
                    return "Facil";
                    break;
                case $dificultad <= 0.6:
                    $this->database->update("UPDATE usuarios SET nivel = 'Intermedio' WHERE id = $idUsuario");
                    return "Medio";
                    break;
                case $dificultad <= 1:
                    $this->database->update("UPDATE usuarios SET nivel = 'Avanzado' WHERE id = $idUsuario");
                    return "Dificil";
                    break;
                default:
                    return null;
                    break;
            }
        } else {
            return null;
        }
    }

}
