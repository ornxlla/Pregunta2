<?php

class PerfilUsuarioModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getUsuarioLogueado($id_usuario)
    {
        $stmt = $this->database->prepare("SELECT * FROM usuario WHERE id_usuario = ?");

        if ($stmt) {
            $stmt->bind_param('i', $id_usuario);
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
}