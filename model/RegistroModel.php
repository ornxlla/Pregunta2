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
    public function darDeAltaUsuario($nombre, $username, $year, $genero, $email, $password, $pais, $ciudad, $nombreImagen)
    {
        $sql = 'INSERT INTO USUARIO
        (nombre_completo, $nombre_usuario, $anio_nacimiento, $genero, $mail, $contrasenia, $imagen_perdil, $pais, $ciudad)
        VALUES (?, ?, ?, ?, ?, ?)';
        $stmt = $this->database->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("isssss", $nombre, $username, $year, $genero, $email, $password, $pais, $ciudad, $nombreImagen);
            return $stmt->execute();
        } else {
            return false;
        }
    }
}