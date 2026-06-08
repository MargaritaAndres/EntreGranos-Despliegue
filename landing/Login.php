<?php
session_start();
include "conexion.php";   // aquí se crea la variable $conexion

// Validar que los datos del formulario lleguen
if (!isset($_POST['email']) || !isset($_POST['contrasena'])) {
    die("Error: No llegaron los datos del formulario.");
}

$correo = $_POST['email'];
$contraseña = $_POST['contrasena'];

//  Usar $conexion, no $con
$sql = mysqli_query($conexion,
    "SELECT * FROM usuarios WHERE email = '$correo' AND password = '$contraseña'"
);

// Verificar consulta
if ($sql) {

    $rowcount = mysqli_num_rows($sql);

    if ($rowcount == 0) {
        echo "Login inválido";
        exit;
    }

    // Obtener datos del usuario
    $row = mysqli_fetch_assoc($sql);

    $_SESSION['id_usuario'] = $row['id'];
    $_SESSION['nombre']     = $row['nombre'];
    $_SESSION['email']      = $row['email'];
    $_SESSION['rol']        = $row['rol'];

    // Redirigir según rol
    if ($row['rol'] === 'admin') {
        header("Location: ../admin/pag_princ_admin.php");
    } else {
        header("Location: productos_usuario.php");
    }
    exit;
    
} else {
    echo "Login fallido: " . mysqli_error($conexion);
}
mysqli_close($conexion);
?>
