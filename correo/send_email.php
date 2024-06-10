<?php

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function generarHashUnico($correo) {

    $salt = 'cadena_aleatoria_unica';
    return hash('sha256', $correo . $salt);
}

function enviarEmailBienvenida($correo, $hash) {
    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->Host       = 'outlook.office365.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'unlam.web2@outlook.com';
        $mail->Password   = 'unlam2024';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;
        $mail->setFrom('unlam.web2@outlook.com');
        $mail->addAddress($correo);

        $mail->isHTML(true);
        $mail->Subject = 'Registro ';
        $mail->Body ='¡Bienvenido! Valida tu cuenta a través del siguiente <a href="http://localhost/registro/activarCuenta?correo='.$correo.'&hash='.$hash.'">LINK</a>. <h2>Tu código de validación es '.$hash.'</h2>.';

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Se produjo un error al enviar el correo. {$mail->ErrorInfo}";
    }
}

$emailUsuario = 'unlam.web2@outlook.com';

$hash = generarHashUnico($emailUsuario);


$resultado = enviarEmailBienvenida($emailUsuario, $hash);
if ($resultado === true) {
    echo 'El correo se ha enviado correctamente.';
} else {
    echo $resultado;
}
?>

