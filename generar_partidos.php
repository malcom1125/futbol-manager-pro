<?php
$host = "localhost";
$usuario = "root";
$contrasena_bd = "";
$nombre_bd = "liga_db";

// Conectar a la base de datos
$conexion = new mysqli($host, $usuario, $contrasena_bd, $nombre_bd);

// Verificar conexión
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Verificar si ya existen partidos
$query_check = "SELECT COUNT(*) as total FROM partidos";
$resultado_check = $conexion->query($query_check);
$row = $resultado_check->fetch_assoc();

if ($row['total'] == 0) { // Si no hay partidos generados
    // Lógica para generar nuevos partidos
    // Ejemplo simple de generación de partidos
    $query_equipos = "SELECT id_equipo FROM equipos";
    $resultado_equipos = $conexion->query($query_equipos);

    $equipos = [];
    while ($equipo = $resultado_equipos->fetch_assoc()) {
        $equipos[] = $equipo['id_equipo'];
    }

    // Generar partidos (ejemplo simple)
    for ($i = 0; $i < count($equipos); $i++) {
        for ($j = $i + 1; $j < count($equipos); $j++) {
            $id_local = $equipos[$i];
            $id_visitante = $equipos[$j];
            $fecha = date('Y-m-d H:i:s', strtotime('+1 day')); // Fecha de un día en el futuro

            $query_insert = "INSERT INTO partidos (id_equipo_local, id_equipo_visitante, fecha) VALUES (?, ?, ?)";
            $stmt = $conexion->prepare($query_insert);
            $stmt->bind_param("iis", $id_local, $id_visitante, $fecha);
            $stmt->execute();
            $stmt->close();
        }
    }
} 

// Cerrar la conexión
$conexion->close();
?>
