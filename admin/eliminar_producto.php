<?php
include("../landing/conexion.php");

// Verificar si se recibió el ID por POST
if (isset($_POST['id'])) {
    $id = intval($_POST['id']); // Convertir a entero para mayor seguridad

    // Paso 1: Eliminar primero del carrito todos los registros relacionados con ese producto
    mysqli_query($conexion, "DELETE FROM carrito WHERE id_producto = $id");

    // Paso 2: Ahora sí, eliminar el producto del catálogo
    $sql = "DELETE FROM productos WHERE id = $id";
    if (mysqli_query($conexion, $sql)) {
        // Redirigir de vuelta a la lista de productos sin mostrar mensaje
        header("Location: productos_administrador.php");
        exit; // Detiene el script después de redirigir
    } else {
        // Si falla, mostrar mensaje de error
        echo "Error al eliminar: " . mysqli_error($conexion);
    }
} else {
    // Si no se recibió ID, mostrar mensaje
    echo "ID no recibido.";
}
?>
