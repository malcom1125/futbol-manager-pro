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

// Verificar si se ha pasado el id_equipo por GET
if (isset($_GET['id_equipo'])) {
    $id = intval($_GET['id_equipo']); // Asegúrate de que el ID sea un entero

    // Eliminar registros en la tabla de clasificación
    $sql_clasificacion = "DELETE FROM clasificacion WHERE id_equipo = ?";
    if ($stmt_clasificacion = $conexion->prepare($sql_clasificacion)) {
        $stmt_clasificacion->bind_param("i", $id);
        $stmt_clasificacion->execute();
        $stmt_clasificacion->close();
    }

    // Eliminar registros de partidos donde el equipo es local o visitante
    $sql_partidos_local = "DELETE FROM partidos WHERE id_equipo_local = ?";
    $sql_partidos_visitante = "DELETE FROM partidos WHERE id_equipo_visitante = ?";
    
    // Eliminar partidos donde el equipo es local
    if ($stmt_partidos_local = $conexion->prepare($sql_partidos_local)) {
        $stmt_partidos_local->bind_param("i", $id);
        $stmt_partidos_local->execute();
        $stmt_partidos_local->close();
    }

    // Eliminar partidos donde el equipo es visitante
    if ($stmt_partidos_visitante = $conexion->prepare($sql_partidos_visitante)) {
        $stmt_partidos_visitante->bind_param("i", $id);
        $stmt_partidos_visitante->execute();
        $stmt_partidos_visitante->close();
    }

    // Eliminar registros de jugadores
    $sql_jugadores = "DELETE FROM jugadores WHERE id_equipo = ?";
    if ($stmt_jugadores = $conexion->prepare($sql_jugadores)) {
        $stmt_jugadores->bind_param("i", $id);
        $stmt_jugadores->execute();
        $stmt_jugadores->close();
    }

    // Eliminar el equipo
    $sql_equipo = "DELETE FROM equipos WHERE id_equipo = ?";
    if ($stmt_equipo = $conexion->prepare($sql_equipo)) {
        $stmt_equipo->bind_param("i", $id);
        if ($stmt_equipo->execute()) {
            $_SESSION['message'] = 'Equipo y sus registros eliminados correctamente';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Error al eliminar el equipo: ' . $stmt_equipo->error;
            $_SESSION['message_type'] = 'danger';
        }
        $stmt_equipo->close();
    } else {
        $_SESSION['message'] = 'Error en la preparación de la consulta: ' . $conexion->error;
        $_SESSION['message_type'] = 'danger';
    }
}

// Cerrar la conexión
$conexion->close();

// Redirigir a la página de equipos
header("Location: equipos.php");
exit();
?>
