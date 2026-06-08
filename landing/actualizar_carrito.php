<?php
include "conexion.php";
session_start();

if (!isset($_SESSION['id_usuario'])) {
    echo "error";   // sin sesión
    exit;
}

if (isset($_POST['id_carrito'], $_POST['nueva_cantidad'])) {
    $id_carrito     = (int)$_POST['id_carrito'];
    $nueva_cantidad = max(1, (int)$_POST['nueva_cantidad']);   // nunca < 1

    $sql = "UPDATE carrito
            SET cantidad_productos = $nueva_cantidad
            WHERE id_carrito = $id_carrito";

    echo mysqli_query($conexion, $sql) ? "ok" : "error";
} else {
    echo "error";
}
