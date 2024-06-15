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
        $query = "SELECT * FROM usuario WHERE nombre_usuario = '$username' AND contrasenia = '$password'";
        return $this->database->query($query);
    }

    public function getUsuario($username){
        $query = "SELECT * FROM usuario WHERE nombre_usuario = '$username'";
        return $this->database->query($query);
    }

   /* public function setUserVerified($token) {
        $query =  "UPDATE usuario SET esta_verificado = 'true' WHERE verify_token = '$token'";
        return $this->database->update($query);
    }*/

   /* public function actualizarCoordenadas($latitud,$longitud,$idUsuario){
        $query = "UPDATE usuario SET latitud = '$latitud', longitud = '$longitud' WHERE id = '$idUsuario'";
        return $this->database->update($query);
    }*/




    // METODOS DEL PreguntaController provisoriamente acá hasta que funcione el PreguntaModel

    public function obtenerPreguntas()
    {
        $query = "SELECT * FROM  preguntas_sugeridas";
        return$this->database->query($query);
    }

    public function aceptarPregunta($pregunta){
        $query = "INSERT INTO preguntas (pregunta_texto, id_tematica, utilizada, contador_respuestas_correctas, contador_respuestas_incorrectas, id_dificultad, estado, apariciones) VALUES ('$pregunta', 1, 0, 0, 0, 1, 1, 0)";
        $this->database->query($query);
    }
/*
    public function eliminarPregunta($idPregunta){
        $query = "DELETE FROM preguntas WHERE id_pregunta = '$idPregunta'";
        $this->database->query($query);
    }*/





    // El editor puede revisar las preguntas reportadas para aprobar o dar de baja:



    public function getPreguntasReportadas()
    {
        $query = "SELECT id_pregunta, pregunta_texto FROM preguntas WHERE reportada = 1";
        $stmt = $this->database->prepare($query);
        $stmt->execute();

        $result = $stmt->get_result(); // Obtenemos el resultado como objeto mysqli_result
        $preguntasReportadas = [];

        while ($row = $result->fetch_assoc()) {
            $preguntasReportadas[] = $row; // Almacenamos cada fila en el array
        }

        return $preguntasReportadas;
    }



    public function aprobarPregunta($idPregunta)
    {
        // Consulta para aprobar la pregunta (marcar como no reportada)
        $stmt = $this->database->prepare("UPDATE preguntas SET reportada = 0 WHERE id_pregunta = :idPregunta");
        $stmt->bindParam(':idPregunta', $idPregunta);
        $stmt->execute();
    }

    public function eliminarPregunta($idPregunta)
    {
        // Consulta para eliminar la pregunta
        $stmt = $this->database->prepare("DELETE FROM preguntas WHERE id_pregunta = :idPregunta");
        $stmt->bindParam(':idPregunta', $idPregunta);
        $stmt->execute();
    }




}