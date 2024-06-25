<?php

namespace model;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/src/Exception.php';
require 'vendor/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/src/SMTP.php';
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

    public function altaUsuario($username, $password, $correo, $hash){
        $sql = "INSERT INTO login (username, password, correo, rol, activado, hash)
                VALUES (?, ?, ?, ?, ?, ?)";
        $rol = 1;
        $activado = 0;

        $stmt = $this->database->prepare($sql);
        if($stmt){
            $stmt->bind_param("sssiis", $username, $password, $correo, $rol, $activado, $hash);
            return $stmt->execute();
        }else{
            return false;
        }
    }




    public function altaUsuario_datos($id_usuario, $nombre, $nacimiento, $genero, $imagen_perfil, $pais, $ciudad, $latitud, $longitud) {
        // Obtener la fecha actual
        $fecha_registro = date('Y-m-d H:i:s');

        $sql = "INSERT INTO datos_usuario (id_usuario, nombre, nacimiento, genero, imagen_perfil, pais, ciudad, latitud, longitud, fecha_registro)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->database->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("isssssssss", $id_usuario, $nombre, $nacimiento, $genero, $imagen_perfil, $pais, $ciudad, $latitud, $longitud, $fecha_registro);

            return $stmt->execute();
        } else {
            return false;
        }
    }


    public function obtenerUsuario($username){
        $query = "SELECT id_usuario FROM login WHERE username = ?";
        $stmt = $this->database->prepare($query);
        if($stmt){
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                return $result->fetch_assoc();
            } else {
                return null; // Usuario no encontrado
            }
        } else {
            return null; // Error en la preparación de la consulta
        }
    }


    public function enviarCorreoConfirmacion($correoUsuario, $codigoValidacion)
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'outlook.office365.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'unlam.web2@outlook.com';
            $mail->Password   = 'unlam2024';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;
            $mail->setFrom('unlam.web2@outlook.com');
            $mail->addAddress($correoUsuario);
            $mail->Subject = 'Correo de validacion';

            $mail->Body = "¡Gracias por registrarte en nuestro sitio! Por favor, haz clic en el siguiente enlace para confirmar tu cuenta: http://localhost/Registro/validacion?code=" .$codigoValidacion ;

            $mail->send();
            return true;
        } catch (Exception $e) {
            $error = 'Error al enviar correo electrónico: ' . $mail->ErrorInfo;
            error_log($error);
            return $error;
        }
    }

    public function validarUsuario($codigoValidacion)
    {
        $query = "SELECT * FROM login WHERE hash = ? AND activado = 0";
        $stmt = $this->database->prepare($query);
        if($stmt){
            $stmt->bind_param("s", $codigoValidacion);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                // Actualiza el estado "activado" del usuario en la base de datos
                $sqlUpdate = 'UPDATE login SET activado = 1 WHERE hash = ?';
                $stmtUpdate = $this->database->prepare($sqlUpdate);
                $stmtUpdate->bind_param("s", $codigoValidacion);
                $stmtUpdate->execute();

                return true;
            } else {
                return false; // Código de validación no válido o usuario ya activado
            }
        } else {
            return false; // Error en la preparación de la consulta
        }
    }
}