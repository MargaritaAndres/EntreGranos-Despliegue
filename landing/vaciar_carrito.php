<?php
session_start(); // Inicia sesión para acceder al ID del usuario
include("conexion.php"); // Conexión a la base de datos

// Verifica que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: Login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario']; // Obtener el ID del usuario

// Vaciar solo el carrito de este usuario
$sql = "DELETE FROM carrito WHERE id_usuario = $id_usuario";
if (mysqli_query($conexion, $sql)) {
    header("Location: carrito.php");
    exit;
} else {
    echo "Error al vaciar carrito: " . mysqli_error($con);
}
?>
