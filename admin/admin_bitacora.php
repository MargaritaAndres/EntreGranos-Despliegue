<?php
include '../landing/conexion.php';

// Consulta con limpieza de llaves y comillas
$sql = "SELECT
            tabla_afectada,
            accion,
            id_registro,
            usuario_responsable,
            REPLACE(REPLACE(REPLACE(datos_anteriores, '\"', ''), '{', ''), '}', '') AS datos_anteriores_limpios,
            REPLACE(REPLACE(REPLACE(datos_nuevos, '\"', ''), '{', ''), '}', '') AS datos_nuevos_limpios,
            fecha
        FROM bitacora_general
        ORDER BY fecha DESC";

$resultado = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - Termo Briss</title>
    <link rel="stylesheet" href="admin_bitacora.css">
</head>
<body>
    <h1>Panel de Administración</h1>
    <div class="saludo">
        <strong>Bienvenida al Panel de Administración</strong> 👋
    </div>

    <div class="bitacora">
        <h2>Bitácora de Cambios</h2>
        <table>
            <tr>
                <th>Tabla</th>
                <th>Acción</th>
                <th>ID Afectado</th>
                <th>Responsable</th>
                <th>Datos Anteriores</th>
                <th>Datos Nuevos</th>
                <th>Fecha</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($resultado)): ?>
            <tr>
                <td><?= $row['tabla_afectada'] ?></td>
                <td><?= $row['accion'] ?></td>
                <td><?= $row['id_registro'] ?></td>
                <td><?= $row['usuario_responsable'] ?></td>
                <td><?= $row['datos_anteriores_limpios'] ?: 'sin historial'?></td>
                <td><?= $row['datos_nuevos_limpios'] ?: 'sin datos' ?></td>
                <td><?= $row['fecha'] ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <div class="salir">
        <a href="pag_princ_admin.php">Volver al inicio</a>
    </div>
</body>
</html>

