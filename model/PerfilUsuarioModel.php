<?php

class PerfilUsuarioModel
{   private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }
    public function getUsuarioLogueado($id_usuario)
    {
        return $this->database->query('SELECT * FROM usuario WHERE id_usuario = $id_usuario');
    }
}