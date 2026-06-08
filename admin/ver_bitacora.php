<?php
include 'conexion.php'; // Tu conexión a la BD

$sql = "SELECT * FROM bitacora_general ORDER BY fecha DESC";
$result = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bitácora General</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Historial de Bitácora</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Tabla</th>
            <th>Acción</th>
            <th>ID Registro</th>
            <th>Datos Anteriores</th>
            <th>Datos Nuevos</th>
            <th>Responsable</th>
            <th>Fecha</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['tabla_afectada'] ?></td>
            <td><?= $row['accion'] ?></td>
            <td><?= $row['id_registro'] ?></td>
            <td><?= $row['datos_anteriores'] ?></td>
            <td><?= $row['datos_nuevos'] ?></td>
            <td><?= $row['usuario_responsable'] ?></td>
            <td><?= $row['fecha'] ?></td>
        </tr>
        <?php endwhile; ?>

    </table>
</body>
</html>
