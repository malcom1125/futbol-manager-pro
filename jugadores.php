<?php
// Conectar a la base de datos
$host = "localhost";  // Cambia por tu host
$usuario = "root";     // Cambia por tu usuario
$contrasena_bd = "";   // Cambia por tu contraseña de base de datos
$nombre_bd = "liga_db"; // Cambia por el nombre de tu base de datos
$conexion = new mysqli($host, $usuario, $contrasena_bd, $nombre_bd);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Consulta para obtener los jugadores y sus equipos
$consulta = "
    SELECT j.id_jugador, j.nombre, j.apellido, j.fecha_nacimiento, j.posicion, j.dorsal, e.nombre 
    FROM jugadores j 
    JOIN equipos e ON j.id_equipo = e.id_equipo"; // Asegúrate de que los nombres de las tablas y columnas sean correctos
$resultado = $conexion->query($consulta);

// Verificar si la consulta fue exitosa
if (!$resultado) {
    die("Error en la consulta: " . $conexion->error);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liga - Jugadores</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f4f8;
        }
        nav {
            background-color: #4584db;
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .logo{
           font-size: 30px; 
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .logo {
            color: white;
            font-weight: bold;
            font-size: 1.5rem;
            text-decoration: none;
        }
        h2 {
            color: #2563eb;
            margin-bottom: 1rem;
        }
        table {
            width: 100%;
            margin-top: 2rem;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #2563eb;
            color: white;
            font-weight: bold;
        }
        td {
            background-color: white;
        }
        .acciones button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .acciones .editar {
            background-color: #4CAF50;
            color: white;
        }
        .acciones .editar:hover {
            background-color: #45a049;
        }
        .acciones .eliminar {
            background-color: #f44336;
            color: white;
        }
        .acciones .eliminar:hover {
            background-color: #e53935;
        }
        footer {
            background-color: #4584db;
            color: white;
            text-align: center;
            padding: 1rem;
            margin-top: 20px;
        }
        /* Estilos generales de los botones */
        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }
        .btn-editar {
            background-color: #007bff;
            color: white;
        }
        .btn-editar:hover {
            background-color: #0056b3;
        }
        .btn-eliminar {
            background-color: #dc3545;
            color: white;
        }
        .btn-eliminar:hover {
            background-color: #c82333;
        }
        .btn i {
            margin-right: 5px;
        }
    </style>
</head>
<body>
<nav>
<div class="container">
            <nav>
                    <a href="inicio.php" class="logo">Liga</a>
                    <a href="equipos.php">Equipos</a>
                    <a href="clasificacion.php">Clasificación</a>
                    <a href="jugadores.php">Mirar Jugadores</a>
            </nav>
        </div>
</nav>
<div class="container">
    <h2>Jugadores Registrados</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID Jugador</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Fecha de Nacimiento</th>
                <th>Posición</th>
                <th>Dorsal</th>
                <th>Equipo</th> <!-- Nueva columna para el equipo -->
            </tr>
        </thead>
        <tbody>
            <?php
            // Verifica si hay resultados antes de intentar acceder a ellos
            if ($resultado->num_rows > 0) {
                while ($row = $resultado->fetch_assoc()) {
            ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id_jugador']); ?></td>
                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                <td><?php echo htmlspecialchars($row['apellido']); ?></td>
                <td><?php echo htmlspecialchars($row['fecha_nacimiento']); ?></td>
                <td><?php echo htmlspecialchars($row['posicion']); ?></td>
                <td><?php echo htmlspecialchars($row['dorsal']); ?></td>
                <td><?php echo htmlspecialchars($row['nombre']); ?></td> <!-- Mostrar nombre del equipo -->
            </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='7'>No hay jugadores disponibles.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<footer>
    <div class="container">
        <p>&copy; prom 2024</p>
    </div>
</footer>
</body>
</html>
