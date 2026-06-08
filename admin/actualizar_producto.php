<?php
include "../landing/conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $descripcion = $_POST['descripcion'];
    $imagen = $_POST['imagen'];

    $sql = "UPDATE productos SET
            nombre = '$nombre',
            precio = '$precio',
            descripcion = '$descripcion',
            imagen = '$imagen'
            WHERE id = $id";

    if (mysqli_query($conexion, $sql)) {
        mysqli_close($conexion); // Cierra conexión antes de redirigir
        header("Location: productos_administrador.php");
        exit(); // Importante para detener el script
    } else {
        echo "Error al actualizar: " . mysqli_error($conexion);
    }
}
?>
