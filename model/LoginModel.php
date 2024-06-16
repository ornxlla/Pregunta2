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





// *********************** PreguntaMODEL:*********************************
//PREGUNTAS REPORTADAS: puede revisar las preguntas reportadas, para aprobar o dar de baja

// paso 1
    public function obtenerPreguntasReportadas()
    {
        $query = "SELECT id_pregunta, pregunta_texto FROM preguntas WHERE reportada = 1";
        return $this->database->query($query);
    }

//paso 2 aprobar pregunta

    public function aprobarPregunta($idPregunta)
    {
        $query = "UPDATE preguntas SET reportada = 0 WHERE id_pregunta = '$idPregunta'";
        $this->database->execute($query);
    }

//paso 3 eliminar pregunta

    public function darDeBajaPregunta($idPregunta)
    {
        $query = "DELETE FROM preguntas WHERE id_pregunta = '$idPregunta'";
        $this->database->execute($query);
    }


// PREGUNTAS SUGERIDAS : // el editor puede aprobar las preguntas sugeridas por usuarios

    public function obtenerPreguntasSugeridas()
    {
        $query = "SELECT id_pregunta, pregunta_texto FROM preguntas WHERE es_sugerida = 1";
        return $this->database->query($query);
    }


    public function aprobarPreguntaSugerida($idPregunta)
    {
        $query = "UPDATE preguntas SET es_sugerida = 0 WHERE id_pregunta = $idPregunta";
        $this->database->execute($query);
    }


// el editor podrá ver los cambios de sus funciones en el listado general de preguntas, es decir que trae las preguntas que no son sugeridas ni reportadas  (porque cuando las aceptó dichos campos pasaron a estar en false)

    public function obtenerPreguntasGenerales()
    {
        $query = "SELECT id_pregunta, pregunta_texto FROM preguntas WHERE es_sugerida = 0 AND reportada = 0";
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

        $query = "INSERT INTO preguntas (pregunta_texto, id_dificultad, id_tematica, utilizada, contador_respuestas_correctas, contador_respuestas_incorrectas, estado, apariciones, reportada, es_sugerida) 
                  VALUES (?, ?, ?, 0, 0, 0, 1, 0, 0, 0)";

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
        $query = "DELETE FROM preguntas WHERE id_pregunta = '$idPregunta'";
        $this->database->execute($query);
    }











}