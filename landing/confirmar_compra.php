<?php
session_start();
include("conexion.php");
require('fpdf.php');

// Verificar que el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    die("Debes iniciar sesión para confirmar tu compra.");
}
$id_usuario = $_SESSION['id_usuario'];
$nombre = $_SESSION['nombre'] ?? 'Cliente';
$email = $_SESSION['email'] ?? 'correo@ejemplo.com'; // Requiere haber guardado esto en $_SESSION

// Obtener los productos del carrito del usuario actual
$query = mysqli_query($conexion, "
    SELECT p.nombre AS producto, c.cantidad_productos AS cantidad, c.precio
    FROM carrito c
    JOIN productos p ON c.id_producto = p.id
    WHERE c.id_usuario = $id_usuario
");
if (mysqli_num_rows($query) === 0) {
    die("Tu carrito está vacío.");
}
// === Clase personalizada para el PDF ===
class PDF extends FPDF {
    function Header() {
        $logoWidth = 40;
        $x = ($this->GetPageWidth() - $logoWidth) / 2;
        $this->Image('imagen/LOGO TERMO BRISS.jpeg', $x, 10, $logoWidth);
        $this->Ln(30);

        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, "Termo Briss", 0, 1, 'C');

        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 10, "Fecha: " . date("d/m/Y H:i"), 0, 1, 'C');
        $this->Ln(5);

        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, "Ticket de Compra", 0, 1, 'C');
        $this->Ln(5);
    }
    function Footer() {
        $this->SetY(-20);
        $this->SetFont('Arial', 'I', 9);
        $this->Cell(0, 10, "Gracias por tu compra, ¡vuelve pronto!", 0, 0, 'C');
    }
}

// === Crear y llenar el PDF ===
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(20, 20, 20);
$pdf->AddPage();

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 8, "Cliente: $nombre", 0, 1, 'L');
$pdf->Ln(3);

// Encabezado de tabla
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(80, 7, 'Producto', 0);
$pdf->Cell(25, 7, 'Cant.', 0, 0, 'C');
$pdf->Cell(35, 7, 'Subtotal', 0, 1, 'R');
$pdf->Ln(2);

// Detalles de los productos
$total = 0;
$pdf->SetFont('Arial', '', 10);
while ($row = mysqli_fetch_assoc($query)) {
    $producto = $row['producto'];
    $cantidad = $row['cantidad'];
    $precio = $row['precio'];
    $subtotal = $cantidad * $precio;
    $total += $subtotal;

    $pdf->Cell(80, 6, $producto, 0);
    $pdf->Cell(25, 6, $cantidad, 0, 0, 'C');
    $pdf->Cell(35, 6, '$' . number_format($subtotal, 2), 0, 1, 'R');
}

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 6, '--------------------------------------------', 0, 1, 'C');
$pdf->Ln(1);

// Cálculos finales
$iva = $total * 0.16;
$pdf->Cell(120, 8, 'Subtotal:', 0, 0, 'R');
$pdf->Cell(20, 8, '$' . number_format($total, 2), 0, 1, 'R');
$pdf->Cell(120, 8, 'IVA (16%):', 0, 0, 'R');
$pdf->Cell(20, 8, '$' . number_format($iva, 2), 0, 1, 'R');
$pdf->Cell(120, 8, 'Total:', 0, 0, 'R');
$pdf->Cell(20, 8, '$' . number_format($total + $iva, 2), 0, 1, 'R');

// Guardar el PDF
$archivoPDF = 'ticket_' . time() . '.pdf';
$pdf->Output('F', $archivoPDF);

// === Enviar por correo ===
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'termobriss@gmail.com';         // Correo creada para
    $mail->Password = 'bjuqysvpwpwyrazz';        // Contraseña de app generada desde google (cuenta) de termo briss
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('termobriss@gmail.com', 'Termo Briss');
    $mail->addAddress($email);

    $mail->Subject = 'Tu comprobante de compra - Termo Briss';
    $mail->Body = "Hola $nombre,\n\nGracias por tu compra. Adjunto encontrarás tu ticket.\n\n¡Disfruta tu termo!";
    $mail->addAttachment($archivoPDF);

    $mail->send();
} catch (Exception $e) {
    error_log("Error al enviar el correo: {$mail->ErrorInfo}");
}

// Eliminar archivo temporal y vaciar carrito
unlink($archivoPDF);
mysqli_query($conexion, "DELETE FROM carrito WHERE id_usuario = $id_usuario");
?>

<!-- ====== Página de confirmación ====== -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Compra realizada</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 60px;
            background-color: #f3f3f3;
        }
        h1 {
            color: #1d104d;
        }
        p {
            font-size: 18px;
        }
    </style>
</head>
<body>
    <h1>¡Gracias por tu compra!</h1>
    <p>Tu pedido ha sido registrado y el ticket fue enviado a tu correo.</p>
    <a href="productos_usuario.php">← Volver a productos</a>
</body>
</html>

