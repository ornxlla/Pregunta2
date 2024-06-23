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

// *********************** PreguntaMODEL:*********************************
//PREGUNTAS REPORTADAS: puede revisar las preguntas reportadas, para aprobar o dar de baja

































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
