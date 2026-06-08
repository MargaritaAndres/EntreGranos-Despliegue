<?php
include("conexion.php");

if (isset($_POST['id_carrito'])) {
    $id = $_POST['id_carrito'];
    $sql = "DELETE FROM carrito WHERE id_carrito = $id";
    if (mysqli_query($conexion, $sql)) {
        header("Location: carrito.php"); // Redirige de nuevo al carrito
    } else {
        echo "Error al eliminar: " . mysqli_error($conexion);
    }
} else {
    echo "ID no recibido.";
}
?>
