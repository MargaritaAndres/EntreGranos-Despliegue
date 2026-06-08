<?php
session_start();
include("cone.php");

if (!isset($_SESSION['id_usuario'])) {
    exit("Sin usuario");
}

$id_usuario = $_SESSION['id_usuario'];

$sql = "DELETE FROM carrito WHERE id_usuario = $id_usuario";
if (mysqli_query($con, $sql)) {
    echo "ok";
} else {
    echo "error";
}
