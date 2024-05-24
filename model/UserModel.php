<?php

namespace model;

class UserModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getUsuario($mail, $contrasenia)
    {
        return $this->database->query('
        SELECT * FROM usuario WHERE mail = $mail AND contrasenia = $contrasenia
        ');
    }


  /*  public function validarCredenciales($mail, $contraseniaw)
    {
        $sql = "select * from usuario where mail= '$mail' && contrasenia = '$contrasenia'";
        return $resultado = $this->database->query($sql);

    }*/
}