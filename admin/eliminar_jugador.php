<?php
session_start();

// Configuración de la base de datos
$host = "localhost";  // Cambia por tu host
$usuario = "root";     // Cambia por tu usuario
$contrasena_bd = "";   // Cambia por tu contraseña de base de datos
$nombre_bd = "liga_db"; // Cambia por el nombre de tu base de datos

// Crear conexión
$conexion = new mysqli($host, $usuario, $contrasena_bd, $nombre_bd);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Verificar si se ha pasado el id_jugador por GET
if (isset($_GET['id_jugador'])) {
    $id_jugador = intval($_GET['id_jugador']); // Asegúrate de que el ID sea un entero

    // Eliminar el jugador
    $sql_jugador = "DELETE FROM jugadores WHERE id_jugador = ?";
    if ($stmt_jugador = $conexion->prepare($sql_jugador)) {
        $stmt_jugador->bind_param("i", $id_jugador);
        if ($stmt_jugador->execute()) {
            $_SESSION['message'] = 'Jugador eliminado correctamente';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Error al eliminar el jugador: ' . $stmt_jugador->error;
            $_SESSION['message_type'] = 'danger';
        }
        $stmt_jugador->close();
    } else {
        $_SESSION['message'] = 'Error en la preparación de la consulta: ' . $conexion->error;
        $_SESSION['message_type'] = 'danger';
    }
}

// Cerrar la conexión
$conexion->close();

// Redirigir a la página de jugadores
header("Location: jugadores.php");
exit();
?>
