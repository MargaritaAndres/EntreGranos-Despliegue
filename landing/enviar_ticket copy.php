<?php
session_start();
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* 1. Recuperar datos de sesión */
$archivo = $_SESSION['archivo_pdf'] ?? '';
$nombre  = $_SESSION['nombre']      ?? 'Cliente';
$email   = $_SESSION['email']       ?? '';

if (!$archivo || !file_exists($archivo)) {
    $mensaje = "No se encontró el PDF.";
} else {
    // 2. Enviar correo con PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'termobriss@gmail.com';
        $mail->Password   = 'bjuqysvpwpwyrazz'; // ← sin espacios
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;
        $mail->setFrom('termobriss@gmail.com', 'Termo Briss');
        $mail->addAddress($email);
        $mail->Subject = 'Tu comprobante de compra - Termo Briss';
        $mail->Body    = "Hola $nombre,\n\nGracias por tu compra. Adjuntamos tu ticket en PDF.\n\n¡Disfruta tu termo!";
        $mail->addAttachment($archivo);
        $mail->send();
        $mensaje = "Ticket enviado exitosamente a $email.";
    } catch (Exception $e) {
        $mensaje = "Error al enviar: {$mail->ErrorInfo}";
    }
    // 3. Limpiar archivo y sesión
    unlink($archivo);
    unset($_SESSION['archivo_pdf']);
}
?>
<!-- 4. Mostrar resultado con diseño -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado del envío</title>
    <link rel="stylesheet" href="enviar_ticket.css">
</head>
<body>
    <h1><?= htmlspecialchars($mensaje) ?></h1>
    <a href="productos_usuario.php">← Volver a productos</a>
</body>
</html>
