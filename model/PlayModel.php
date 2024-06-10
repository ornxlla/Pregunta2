<?php

class PlayModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getUsuarioLogueado($username)
    {
        $stmt = $this->database->prepare("SELECT * FROM usuario WHERE nombre_usuario = ?");
        if ($stmt) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                return $result->fetch_assoc();
            } else {
                echo "Error ejecutando la consulta: " . $stmt->error;
                return false;
            }
        } else {
            echo "Error preparando la consulta: " . $this->database->error;
            return false;
        }
    }

    public function getPregunta($dificultad)
    {
        if (in_array($dificultad, [1, 2, 3])) {
            $sql = "SELECT preguntas.*, t.nombre AS nombre_tematica FROM preguntas JOIN tematicas t ON preguntas.id_tematica = t.id_tematica WHERE id_dificultad = ? AND apariciones = 0 ORDER BY RAND() LIMIT 1";
            $stmt = $this->database->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("i", $dificultad);
                $stmt->execute();
                $pregunta = $stmt->get_result()->fetch_assoc();
                $stmt->close();

                if (!empty($pregunta)) {
                    return $pregunta;
                } else {
                    echo "No hay más preguntas para tu nivel.";
                    return false;
                }
            } else {
                echo "Error al preparar la consulta: " . $this->database->error;
                return false;
            }
        } else {
            echo "Nivel de dificultad no válido.";
            return false;
        }
    }


    public function getRespuesta($id_pregunta)
    {
        $sql = "SELECT * FROM respuesta WHERE id_pregunta = ?";
        $stmt = $this->database->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $id_pregunta);
            $stmt->execute();
            $respuesta = $stmt->get_result();
            $stmt->close();

            return $respuesta->fetch_all(MYSQLI_ASSOC);
        } else {
            echo "Error al preparar la consulta: " . $this->database->error;
            return false;
        }
    }

    public function validarRespuesta($id_respuesta)
    {
        $sql = "SELECT * FROM respuesta WHERE id_respuesta = ? AND correcta = 1";
        $stmt = $this->database->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $id_respuesta);
            $stmt->execute();
            $result = $stmt->get_result();
            $respuesta = $result->fetch_assoc();
            $stmt->close();

            if ($respuesta) {
                return true;
            } else {
                return false;
            }
        } else {
            echo "Error al preparar la consulta: " . $this->database->error;
            return false;
        }
    }

    public function sumarApariciones($id_pregunta)
    {
        $sql = "UPDATE preguntas SET apariciones = apariciones + 1 WHERE id_pregunta = ?";
        $stmt = $this->database->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $id_pregunta);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "Error al preparar la consulta: " . $this->database->error;
        }
    }

    public function contadorDeRespuestasCorrectas($id_pregunta)
    {
        $sql = "UPDATE preguntas SET contador_respuestas_correctas = contador_respuestas_correctas + 1 WHERE id_pregunta = ?";
        $stmt = $this->database->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $id_pregunta);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "Error al preparar la consulta: " . $this->database->error;
        }
    }

    public function contadorDeRespuestasIncorrectas($id_pregunta)
    {
        $sql = "UPDATE preguntas SET contador_respuestas_incorrectas = contador_respuestas_incorrectas + 1 WHERE id_pregunta = ?";
        $stmt = $this->database->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $id_pregunta);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "Error al preparar la consulta: " . $this->database->error;
        }
    }

    public function calcularPuntosDelJugador($id_usuario, $puntaje)
    {
        $sql = "UPDATE usuario SET puntaje = puntaje + ? WHERE id_usuario = ?";
        $stmt = $this->database->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ii", $puntaje, $id_usuario);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "Error al preparar la consulta: " . $this->database->error;
        }
    }

    public function agregarPartida($id_jugador_1, $puntos_jugador_1)
    {
        $sql = "INSERT INTO partida (id_jugador_1, puntos_jugador_1) VALUES (?, ?)";
        $stmt = $this->database->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ii", $id_jugador_1, $puntos_jugador_1);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "Error al preparar la consulta: " . $this->database->error;
        }
    }
}