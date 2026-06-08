<?php
include("conexion.php");
session_start();

// Validar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    die("Usuario no autenticado.");
}
$id_usuario = $_SESSION['id_usuario'];
// Validar que se haya enviado el producto
if (isset($_POST['id_producto']) && isset($_POST['cantidad'])) {

    $id_producto = intval($_POST['id_producto']);
    $cantidad = intval($_POST['cantidad']);
    // Obtener el precio del producto
    $producto = mysqli_query($conexion, "SELECT precio FROM productos WHERE id = $id_producto");

    if ($row = mysqli_fetch_assoc($producto)) {
        $precio = $row['precio'];
        // Verificar si el producto ya está en el carrito
        $verificar = mysqli_query($conexion,
            "SELECT * FROM carrito
            WHERE id_usuario = $id_usuario AND id_producto = $id_producto"
        );
        if (mysqli_num_rows($verificar) > 0) {
            // Ya existe → aumentar cantidad
            $sql = "UPDATE carrito
                    SET cantidad_productos = cantidad_productos + $cantidad
                    WHERE id_usuario = $id_usuario
                    AND id_producto = $id_producto";
        } else {
            // No existe → agregar nuevo
            $sql = "INSERT INTO carrito (id_usuario, id_producto, cantidad_productos, precio)
                    VALUES ($id_usuario, $id_producto, $cantidad, $precio)";
        }
        if (mysqli_query($conexion, $sql)) {
            header("Location: carrito.php");
            exit;
        } else {
            echo "Error al agregar al carrito: " . mysqli_error($conexion);
        }
    } else {
        echo "Producto no encontrado.";
    }
} else {
    echo "Datos incompletos para agregar al carrito.";
}
?>
