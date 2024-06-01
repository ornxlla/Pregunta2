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
    public function darDeAltaUsuario($nombre, $username, $year, $genero, $email, $password , $nombreImagen, $pais, $ciudad)
    {
        //ARREGLAR SQL
        $sql = 'INSERT INTO USUARIO
        ( nombre_completo, nombre_usuario, anio_nacimiento, genero, mail, contrasenia, imagen_perfil, país, ciudad)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = $this->database->prepare($sql);

        if ($stmt) {
            $stmt->bind_param( $nombre, $username, $year, $genero, $email, $password, $nombreImagen, $pais, $ciudad );
            return $stmt->execute();
        } else {
            return false;
        }
    }
}