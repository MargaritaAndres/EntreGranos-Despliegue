<?php
session_start(); // Inicia sesión para poder destruirla
session_unset(); // Limpia todas las variables de sesión
session_destroy(); // Destruye la sesión por completo

// Redirige al login
header("Location: Login.html");
exit;
?>
