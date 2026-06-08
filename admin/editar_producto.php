<?php
include "../landing/conexion.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="editar_producto.css">
    <title>Editar producto</title>
</head>
<body>
<div class="logo">
    <img src="../imagen/LOGO2 ENTRE GRANOS.jpg" alt="EntreGranos">
</div>

<?php
// Verifica correctamente si viene por POST
if (isset($_POST['id'])) {
    $id = $_POST['id']; //  Leer desde POST correctamente

    $sql = "SELECT * FROM productos WHERE id = $id";
    $resultado = mysqli_query($conexion, $sql);

    if ($row = mysqli_fetch_assoc($resultado)) {
        ?>
        <h2>Editar Producto</h2>
        <form action="actualizar_producto.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?php echo $row['nombre']; ?>" required><br>

            <label>Precio:</label>
            <input type="number" step="0.01" name="precio" value="<?php echo $row['precio']; ?>" required><br>

            <label>Descripción:</label>
            <textarea name="descripcion"><?php echo $row['descripcion']; ?></textarea><br>

            <label>Imagen (URL o ruta):</label>
            <input type="text" name="imagen" value="<?php echo $row['imagen']; ?>" required><br>

            <button type="submit">Actualizar</button>
        </form>
        <?php
    } else {
        echo "<p>Producto no encontrado.</p>";
    }
} else {
    echo "<p>ID no especificado.</p>";
}
?>
</body>
</html>
