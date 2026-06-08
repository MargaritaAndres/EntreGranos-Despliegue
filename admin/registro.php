<?php
include "../landing/conexion.php";

// Verifica si el formulario fue enviado mediante el método POST
if ($_SERVER["REQUEST_METHOD"] == "POST"){

    // Verificar que todos los datos existan
    if (isset($_POST['nombre'], $_POST['precio'], $_POST['descripcion'], $_POST['imagen'])) {

        // Recupera los valores del formulario
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $descripcion = $_POST['descripcion'];
        $imagen = "../imagen/" . $_POST['imagen']; // Agrega la carpeta 'imagen/' automáticamente

        // Prepara la consulta SQL para insertar el producto en la base de datos
        $sql = "INSERT INTO productos(nombre, precio, descripcion, imagen) VALUES ('$nombre', '$precio', '$descripcion', '$imagen')";
        
        // Ejecuta la consulta y verifica si fue exitosa
        if (mysqli_query($conexion, $sql)) {
            echo "Producto agregado con éxito";
            header("Location: registro_productos.html");
        } else {
            // Si ocurre un error en la consulta, deberiia mostar el mensaje de error
            echo "Error". $sql. "<br>" . mysqli_error($conexion);
        }
    
        mysqli_close($conexion);
}
}
?>