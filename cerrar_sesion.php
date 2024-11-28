<?php
session_start();
session_unset(); // Eliminar todas las variables de sesión
session_destroy(); // Destruir la sesión
header("Location: inicio.php"); // Redirigir al inicio después de cerrar sesión
exit();
?>
