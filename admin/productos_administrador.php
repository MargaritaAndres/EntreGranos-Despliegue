<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="productos_admnistradr.css">
    <title>Productos Admin</title>
</head>
<body>
<div class="container">
    <?php
    include("../landing/conexion.php");
    $sql = mysqli_query($conexion, "SELECT * FROM productos");
    ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Imagen</th>
                <th>Descripción</th>

                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = mysqli_fetch_array($sql)) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['nombre'] ?></td>
                <td><?= $row['precio'] ?></td>
                <td><?= $row['imagen'] ?></td>
                <td><?= $row['descripcion'] ?></td>

                <td>
                    <form method="POST" action="editar_producto.php" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button type="submit" class="btn-modificar">Modificar</button>
                    </form>
                    <form method="POST" action="eliminar_producto.php" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button type="submit" class="btn-eliminar">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<div class="salir">
        <a href="pag_princ_admin.php">Volver al inicio
        </a>
        </div>
</body>
</html>
