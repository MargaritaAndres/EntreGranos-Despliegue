<?php

include "conexion.php";

$nombre = $_POST['nombre'];
$correo = $_POST['email'];
$p1apellido = $_POST['primer_apellido'];
$p2apellido = $_POST['segundo_apellido'];
$contrasena = $_POST['contrasena'];


$sql = mysqli_query($conexion, "
    INSERT INTO usuarios (nombre, email, primer_apellido, segundo_apellido, password, rol)
    VALUES ('$nombre', '$correo', '$p1apellido', '$p2apellido', '$contrasena', 'usuario')
");


if($sql){
    echo "usuario agregado";
    header("Location: index.html");
}else{
    echo "Error " . $sql . "<br>" . mysqli_error($conexion);
}

mysqli_close($conexion);

?>
