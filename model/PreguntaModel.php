<?php

class PreguntaModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerPreguntasGenerales1()
    {
        $query = "SELECT id_pregunta, pregunta_texto FROM preguntas WHERE es_sugerida = 0 AND reportada = 0";
        return $this->database->query($query);
    }








}

?>


