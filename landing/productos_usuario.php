<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos Usuario</title>
    <link rel="stylesheet" href="productosUsuario.css"> <!-- Enlace correcto -->
    <!-- Iconos FontAwesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<!-- Enlace para usar iconos Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<body>
    <!-- Barra fija arriba -->
    <nav class="barra">
        <div class="logo">Entre Granos</div>
        <div class="menu">
            <span id="saludo-usuario" class="saludo-navbar" data-nombre="<?= htmlspecialchars($_SESSION['nombre']) ?>"></span>
            <a href="cerrar_sesion.php" class="cerrar-button">Cerrar sesion</a>
            <a href="index.html" class="info-button">Cuenta</a>
            <a href="carrito.php" title="Carrito"><i class="fas fa-shopping-cart"></i></a>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container">
    <?php
        include("conexion.php");
        $sql = mysqli_query($conexion, "SELECT * FROM productos");

        while($row = mysqli_fetch_array($sql)) {
            $rutaImagen = $row['imagen'];
            if (str_starts_with($rutaImagen, "C:")) {
                $pos = strpos($rutaImagen, "imagen/");
                if ($pos !== false) {
                    $rutaImagen = substr($rutaImagen, $pos);
                }
            }
    ?>
        <div class="producto">
            <div class="caja">
            <img class="img" src="<?php echo $rutaImagen; ?>" alt="Imagen del producto">
                <h4><?php echo $row['nombre']; ?></h4>
                <p>$<?php echo $row['precio']; ?></p>
                <p><?php echo $row['descripcion']; ?></p>
                <form action="agregar_carrito.php" method="post">
                <input type="hidden" name="id_producto" value="<?= $row['id'] ?>">
                <input type="hidden" name="cantidad" value="1">
                <button type="submit">Agregar al carrito</button>
                </form>

            </div>

        </div>
    <?php } ?>
    </div>
    <script src="saludo.js"></script>
</body>
</html>
