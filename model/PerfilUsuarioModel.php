<?php

class PerfilUsuarioModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getUsuarioLogueado($username)
    {
        $stmt = $this->database->prepare("SELECT * FROM usuario WHERE nombre_usuario = ?");

        if ($stmt) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                return $result->fetch_assoc();
            } else {
                echo "Error ejecutando la consulta: " . $stmt->error;
                return false;
            }
        } else {
            echo "Error preparando la consulta: " . $this->database->error;
            return false;
        }


    }
    public function modificarUsuario($nombre, $username, $year, $genero, $email, $password, $pais, $ciudad, $nombreImagen, $latitud, $longitud)
    {
        $sql = 'UPDATE USUARIO SET nombre_completo = ?, anio_nacimiento = ?, genero = ?, mail = ?, contrasenia = ?, pais = ?, ciudad = ?, latitud = ?, longitud = ?';
        if ($nombreImagen) {
            $sql .= ', imagen_perfil = ?';
        }
        $sql .= ' WHERE nombre_usuario = ?';

        $stmt = $this->database->prepare($sql);

        if ($stmt) {
            if ($nombreImagen) {
                $stmt->bind_param("sssssssssss", $nombre, $year, $genero, $email, $password, $pais, $ciudad, $latitud, $longitud, $nombreImagen, $username);
            } else {
                $stmt->bind_param("ssssssssss", $nombre, $year, $genero, $email, $password, $pais, $ciudad, $latitud, $longitud, $username);
            }
            return $stmt->execute();
        } else {
            echo "Error al preparar la consulta: " . $this->database->error;
            return false;
        }
    }
}