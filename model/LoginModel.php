<?php

class LoginModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function validarCredenciales($username, $password)
    {
        $query = "SELECT id_usuario, username, password, correo, rol, activado FROM login WHERE username = '$username' AND password = '$password'";
        return $this->database->query($query);
    }

    public function getDatosUser($id_user)
    {
        $query = "SELECT data.*, login.rol
                  FROM datos_usuario AS data
                  INNER JOIN login AS login 
                  ON data.id_usuario = login.id_usuario
                  WHERE data.id_usuario = '$id_user'";
        return $this->database->query($query);
    }


    public function getPartidas($id_user) {
        $query = "SELECT pcg.puntos, pcg.fecha, login.username 
              FROM partida_clasica_general AS pcg
              JOIN login AS login
              ON pcg.id_jugador = login.id_usuario 
              WHERE pcg.id_jugador = '$id_user'
              ORDER BY pcg.fecha DESC
              LIMIT 3";
        return $this->database->query($query);
    }

    public function getDuelos($id_user)
    {
        $query = "
        SELECT 
            pdg.puntos_jug1, 
            pdg.puntos_jug2, 
            pdg.fecha, 
            login1.username AS username_jug1, 
            login2.username AS username_jug2
        FROM 
            partida_duelo_general AS pdg
        JOIN 
            login AS login1 ON pdg.id_jug1 = login1.id_usuario
        JOIN 
            login AS login2 ON pdg.id_jug2 = login2.id_usuario
        WHERE 
            pdg.id_jug1 = '$id_user' OR pdg.id_jug2 = '$id_user'
        ORDER BY 
            pdg.fecha DESC
        LIMIT 2";

        return $this->database->query($query);
    }
































    /*
        public function obtenerPreguntaPorId($idPregunta) {
            $query = "SELECT * FROM preguntas WHERE id_pregunta = ?";
            $stmt = $this->database->prepare($query);
            $stmt->bind_param("i", $idPregunta);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }

        public function obtenerRespuestasPorIdPregunta($idPregunta) {
            $query = "SELECT * FROM respuesta WHERE id_pregunta = ?";
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
    */

















}
