<?php

class PerfilUsuarioModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getDataUsuario($id_user){
        $stmt = $this->database->prepare("SELECT data.* , login.username, login.correo 
                FROM datos_usuario AS data 
                INNER JOIN login AS login ON data.id_usuario = login.id_usuario
                WHERE data.id_usuario = ?");
        if ($stmt) {
            $stmt->bind_param('i', $id_user);
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

    public function updateDatosUsuario($id_usuario, $nombre, $nacimiento, $genero, $imagen_perfil, $pais, $ciudad, $latitud, $longitud){
        $sql = 'UPDATE datos_usuario SET nombre = ?, nacimiento = ?, genero = ?, pais = ?, ciudad = ?, latitud = ?, longitud = ?';
        if ($imagen_perfil) {
            $sql .= ', imagen_perfil = ?';
        }
        $sql .= ' WHERE id_usuario = ?';
        $stmt = $this->database->prepare($sql);
        if ($stmt) {
            if ($imagen_perfil) {
                $stmt->bind_param("ssssssssi", $nombre, $nacimiento, $genero, $pais, $ciudad, $latitud, $longitud, $imagen_perfil, $id_usuario);
            } else {
                $stmt->bind_param("sssssssi", $nombre, $nacimiento, $genero, $pais, $ciudad, $latitud, $longitud, $id_usuario);
            }
            $result = $stmt->execute();
            if ($result) {
                return $result;
            } else {
                echo "Error ejecutando la consulta: " . $stmt->error;
                return false;
            }
        } else {
            echo "Error al preparar la consulta: " . $this->database->error;
            return false;
        }

    }

    public function updateCorreo($id_usuario, $correo){
        $stmt = $this->database->prepare("UPDATE login SET correo = ? WHERE id_usuario = ?");
        if ($stmt) {
            $stmt->bind_param('si', $correo, $id_usuario);
            $result = $stmt->execute();
            if ($result) {
                return $result;
            } else {
                echo "Error ejecutando la consulta: " . $stmt->error;
                return false;
            }
        } else {
            echo "Error al preparar la consulta: " . $this->database->error;
            return false;
        }
    }

    public function updateUsername($id_usuario, $username){
        $stmt = $this->database->prepare("UPDATE login SET username = ? WHERE id_usuario = ?");
        if ($stmt) {
            $stmt->bind_param('si', $username, $id_usuario);
            $result = $stmt->execute();
            if ($result) {
                return $result;
            } else {
                echo "Error ejecutando la consulta: " . $stmt->error;
                return false;
            }
        } else {
            echo "Error al preparar la consulta: " . $this->database->error;
            return false;
        }
    }

    public function updatePassword($id_usuario, $password){
        $stmt = $this->database->prepare("UPDATE login SET password = ? WHERE id_usuario = ?");
        if ($stmt) {
            $stmt->bind_param('si', $password, $id_usuario);
            $result = $stmt->execute();
            if ($result) {
                return $result;
            } else {
                echo "Error ejecutando la consulta: " . $stmt->error;
                return false;
            }
        } else {
            echo "Error al preparar la consulta: " . $this->database->error;
            return false;
        }
    }



    public function getPartidas($id_user) {
        $query = "SELECT pcg.puntos, pcg.fecha, login.username 
              FROM partida_clasica_general AS pcg
              JOIN login AS login ON pcg.id_jugador = login.id_usuario 
              WHERE pcg.id_jugador = '$id_user'
              ORDER BY pcg.fecha DESC
              LIMIT 3";
        return $this->database->query($query);
    }
}