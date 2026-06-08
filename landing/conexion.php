<?php
// ==============================================
// Archivo: conexion.php
// Descripción: Conexión al servidor MariaDB
// para el proyecto EntreGranos
// ==============================================

$host = "127.0.0.1";
$usuario = "root";
$contrasena = "";  // sin contraseña
$base_datos = "entregranos";

$conexion = new mysqli($host, $usuario, $contrasena, $base_datos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
} else {
}
?>
