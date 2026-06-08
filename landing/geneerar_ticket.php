<?php
// 1. INICIO Y VALIDACIÓN DE SESIÓN
session_start();
include("conexion.php");          // Conexión a la base de datos
require('fpdf.php');          // Librería FPDF

if (!isset($_SESSION['id_usuario'])) {
    header("Location: Login.php");
    exit;
}
$id_usuario = $_SESSION['id_usuario'];
$nombre     = $_SESSION['nombre'] ?? 'Cliente';
$email      = $_SESSION['email']  ?? 'no-mail@example.com';   // Por si después lo usas

// 2. CONSULTA DEL CARRITO DEL USUARIO
$carrito = mysqli_query($conexion, "
    SELECT p.nombre AS producto,
        c.cantidad_productos AS cantidad,
        c.precio
    FROM carrito c
    JOIN productos p ON c.id_producto = p.id
    WHERE c.id_usuario = $id_usuario
");
if (mysqli_num_rows($carrito) === 0) {
    die("Tu carrito está vacío.");
}
// 3. CLASE PDF PERSONALIZADA
class PDF extends FPDF {
    function Header() {
        // Logo (centrado)
        $logoW = 40;
        $x      = ($this->GetPageWidth() - $logoW) / 2;
        $this->Image('../imagen/LOGO2 ENTRE GRANOS.jpg', $x, 20, $logoW);
        $this->Ln(30);
        // Encabezado
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Entre Granos', 0, 1, 'C');

        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 10, 'Fecha: ' . date('d/m/Y H:i'), 0, 1, 'C');
        $this->Ln(5);

        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Ticket de Compra', 0, 1, 'C');
        $this->Ln(5);
    }
    function Footer() {
        $this->SetY(-20);
        $this->SetFont('Arial', 'I', 9);
        $this->Cell(0, 10, 'Gracias por tu compra, ¡vuelve pronto!', 0, 0, 'C');
    }
}
// 4. CONSTRUCCIÓN DEL PDF
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(20,20,20);
$pdf->AddPage();

// Datos del cliente
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 8, "Cliente: $nombre", 0, 1, 'L');
$pdf->Ln(3);

// Encabezado de tabla
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(80, 7, 'Producto', 0);
$pdf->Cell(25, 7, 'Cant.',   0, 0, 'C');
$pdf->Cell(35, 7, 'Subtotal',0, 1, 'R');
$pdf->Ln(2);

// Detalles
$pdf->SetFont('Arial', '', 10);
$total = 0;

while ($row = mysqli_fetch_assoc($carrito)) {
    $producto = $row['producto'];
    $cantidad = $row['cantidad'];
    $precio   = $row['precio'];
    $subtotal = $cantidad * $precio;
    $total   += $subtotal;

    $pdf->Cell(80, 6, $producto, 0);
    $pdf->Cell(25, 6, $cantidad, 0, 0, 'C');
    $pdf->Cell(35, 6, '$' . number_format($subtotal,2), 0, 1, 'R');
}

// Línea separadora y totales
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 6, '--------------------------------------------', 0, 1,'C');
$pdf->Ln(1);

// Subtotal / IVA / Total
$iva = $total * 0.16;
$pdf->Cell(120, 8, 'Subtotal:', 0, 0, 'R');
$pdf->Cell(20, 8, '$'.number_format($total,2), 0, 1, 'R');
$pdf->Cell(120, 8, 'IVA (16%):', 0, 0, 'R');
$pdf->Cell(20, 8, '$'.number_format($iva,2), 0, 1, 'R');
$pdf->Cell(120, 8, 'Total:', 0, 0, 'R');
$pdf->Cell(20, 8, '$'.number_format($total+$iva,2), 0, 1, 'R');

// 5. GUARDAR EL PDF EN /tickets Y REGISTRAR EN SESIÓN
$folder      = 'tickets';
if (!is_dir($folder)) { mkdir($folder, 0777, true); } // crea la carpeta si no existe

$archivoRel  = $folder . '/ticket_' . time() . '.pdf'; // ruta relativa (para navegador)
$archivoAbs  = __DIR__  . '/' . $archivoRel;           // ruta absoluta (para guardar)

$pdf->Output('F', $archivoAbs);                        // Guarda el archivo
$_SESSION['archivo_pdf'] = $archivoRel;                // Guarda ruta relativa en sesión

// 6. REDIRECCIÓN A LA VISTA PREVIA DEL TICKET
header("Location: mostrar_ticket.php");
exit;
?>
