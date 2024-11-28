<?php
// Conectar a la base de datos
$host = "localhost";
$usuario = "root";
$contrasena_bd = "";
$nombre_bd = "liga_db";
$conexion = new mysqli($host, $usuario, $contrasena_bd, $nombre_bd);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Consultar la clasificación de puntos
$query_clasificacion = "SELECT equipos.nombre, clasificacion.puntos FROM clasificacion JOIN equipos ON clasificacion.id_equipo = equipos.id_equipo ORDER BY clasificacion.puntos DESC";
$resultado_clasificacion = $conexion->query($query_clasificacion);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Liga - Clasificación</title>
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

    <h1>Clasificación</h1>

    <table>
        <thead>
            <tr>
                <th>Nombre del Equipo</th>
                <th>Puntos</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultado_clasificacion->num_rows > 0): ?>
                <?php while ($row = $resultado_clasificacion->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($row['puntos']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2">No hay clasificaciones registradas.</td>
                </tr>
            <?php endif; ?>
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
// Cerrar la conexión
$conexion->close();
?>
