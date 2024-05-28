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

}