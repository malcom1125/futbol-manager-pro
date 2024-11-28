<?php
// Configuración de la conexión a la base de datos
$host = "localhost";          // Cambia por tu host si es necesario
$usuario_bd = "root";        // Cambia por tu usuario de base de datos
$contrasena_bd = "";         // Cambia por tu contraseña de base de datos
$nombre_bd = "liga_db";      // Cambia por el nombre de tu base de datos

// Inicializamos la conexión y variables para almacenar los resultados
$conexion = null;
$resultado = null;
$equipos = [];

// Crear la conexión
$conexion = new mysqli($host, $usuario_bd, $contrasena_bd, $nombre_bd);

// Verificar si hay algún error en la conexión
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Consulta SQL para obtener los equipos (sin número de jugadores y fecha de fundación)
$query = "SELECT nombre AS nombre, estadio AS estadio, ciudad AS ciudad FROM equipos";
$resultado = $conexion->query($query);

// Verificar si la consulta fue exitosa
if ($resultado) {
    // Guardar los equipos en un array
    while ($fila = $resultado->fetch_assoc()) {
        $equipos[] = $fila;
    }
} else {
    // Manejar el error de la consulta
    echo "Error en la consulta: " . $conexion->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liga - Equipos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="container">
        <nav>
            <div>
                <a href="inicio.php" class="logo">Liga</a>
                <a href="equipos.php">Equipos</a>
                <a href="clasificacion.php">Clasificación</a>
                <a href="jugadores.php">Mirar jugadores</a>
            </div>
        </nav>
    </div>
</header>

<h1>Lista de Equipos de Fútbol</h1>

<table>
    <thead>
        <tr>
            <th>Nombre del Equipo</th>
            <th>Estadio</th>
            <th>Ciudad</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (count($equipos) > 0) {
            // Mostrar los equipos en la tabla
            foreach ($equipos as $equipo) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($equipo['nombre']) . "</td>";
                echo "<td>" . htmlspecialchars($equipo['estadio']) . "</td>";
                echo "<td>" . htmlspecialchars($equipo['ciudad']) . "</td>";
                echo "</tr>";
            }
        } else {
            // Si no hay equipos en la base de datos
            echo "<tr><td colspan='3'>No se encontraron equipos</td></tr>";
        }
        ?>
    </tbody>
</table>

<footer>
    <div class="container">
        <p>&copy; prom 2024</p>
    </div>
</footer>

</body>
</html>

<?php
// Cerrar la conexión a la base de datos
$conexion->close();
?>
