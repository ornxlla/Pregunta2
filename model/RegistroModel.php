<?php

namespace model;

class RegistroModel
{ private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getUsuarioRegistrados()
    {
        return $this->database->query('SELECT * FROM usuario');
    }
    public function darDeAltaUsuario($nombre, $username, $year, $genero, $email, $password , $nombreImagen, $pais, $ciudad, $latitud, $longitud)
    {
        //ARREGLAR SQL
        $sql = 'INSERT INTO USUARIO
        ( nombre_usuario, es_administrador, mail, contrasenia, nombre_completo, anio_nacimiento, genero, imagen_perfil, país, ciudad, latitud, longitud)
        VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = $this->database->prepare($sql);

        $rol = 0;

        if ($stmt) {
            $stmt->bind_param("sissssssssss", $username, $rol , $email, $password, $nombre, $year, $genero, $nombreImagen, $pais, $ciudad, $latitud, $longitud);
            return $stmt->execute();
        } else {
            return false;
        }
    }
}