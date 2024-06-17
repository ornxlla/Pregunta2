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

    /*
    public function darDeAltaUsuario($nombre, $username, $year, $genero, $email, $password, $nombreImagen, $pais, $ciudad, $latitud, $longitud, $codigo_validacion)
    {
        //ARREGLAR SQL
        $sql = 'INSERT INTO usuario
    (nombre_usuario, es_administrador, mail, contrasenia, nombre_completo, anio_nacimiento, genero, imagen_perfil, pais, ciudad, latitud, longitud, codigo_validacion)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = $this->database->prepare($sql);

        $rol = 0;

        if ($stmt) {
            $stmt->bind_param("sisssssssssss", $nombre, $rol , $email, $password, $nombreImagen, $nombre, $year, $genero, $pais, $ciudad, $latitud, $longitud, $codigo_validacion);
            return $stmt->execute();
        } else {
            return false;
        }
    }
    */
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

    public function altaUsuario_datos($id_usuario, $nombre, $nacimiento, $genero, $imagen_perfil, $pais, $ciudad, $latitud, $longitud){
        $sql = "INSERT INTO datos_usuario (id_usuario, nombre, nacimiento, genero, imagen_perfil, pais, ciudad, latitud, longitud)
                VALUES (? ,?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->database->prepare($sql);
        $stmt = $this->database->prepare($sql);
        if($stmt){
            $stmt->bind_param("issssssss", $id_usuario, $nombre, $nacimiento, $genero, $imagen_perfil, $pais, $ciudad, $latitud, $longitud);
            return $stmt->execute();
        }else{
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
            if ($result) {
                return $result->fetch_assoc();
            } else {
                echo "Error ejecutando la consulta: " . $stmt->error;
                return false;
            }
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
        $query = "SELECT * FROM login WHERE hash = ?";
        $stmt = $this->database->prepare($query);
        $stmt->bind_param("s", $codigoValidacion);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            //usuario con el código de validación se encontro
            // Actualiza el estado "validado" del usuario en la base de datos
            $row = $result->fetch_assoc();
            $userId = $row['id_usuario'];

            $sqlUpdate = 'UPDATE login SET activado = 1 WHERE id_usuario = ?';
            $stmtUpdate = $this->database->prepare($sqlUpdate);
            $stmtUpdate->bind_param("i", $userId);
            $stmtUpdate->execute();

            return true;
        } else {
            return false;
        }
    }

}