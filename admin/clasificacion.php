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

// Consultar todos los equipos para el menú desplegable
$query_equipos = "SELECT id_equipo, nombre FROM equipos";
$resultado_equipos = $conexion->query($query_equipos);

// Manejar el envío del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_equipo'], $_POST['partidos_ganados'], $_POST['partidos_empatados'], $_POST['partidos_perdidos'])) {
    $id_equipo = (int)$_POST['id_equipo'];
    $partidos_ganados = (int)$_POST['partidos_ganados'];
    $partidos_empatados = (int)$_POST['partidos_empatados'];
    $partidos_perdidos = (int)$_POST['partidos_perdidos'];

    // Calcular puntos
    $puntos = ($partidos_ganados * 3) + ($partidos_empatados * 1);

    // Almacenar puntos en la tabla
    $stmt = $conexion->prepare("INSERT INTO clasificacion (id_equipo, puntos) VALUES (?, ?) ON DUPLICATE KEY UPDATE puntos = ?");
    $stmt->bind_param("iii", $id_equipo, $puntos, $puntos);
    $stmt->execute();
    $stmt->close();

    // Mensaje de éxito
    echo "<script>alert('Puntos almacenados exitosamente: $puntos');</script>";

    // Redirigir para evitar reenvío del formulario
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Consultar la clasificación con el nombre del equipo
$query_clasificacion = "SELECT c.id_equipo, c.puntos, e.nombre FROM clasificacion c JOIN equipos e ON c.id_equipo = e.id_equipo";
$resultado_clasificacion = $conexion->query($query_clasificacion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clasificación</title>
    <style>
        /* Estilos básicos */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f4f8;
        }
        .navbar {
            background-color: #2563eb;
            padding: 10px;
            text-align: center;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .contenedor {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2563eb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #2563eb;
            color: white;
        }
        /* Formulario */
        .form-container {
            margin-top: 20px;
        }
        input[type="number"], select {
            width: 100%;
            padding: 8px;
            margin: 4px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .navbar {
            background-color: #4584db;
            padding: 30px;
            text-align: left;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
        }
        nav {
            background-color: #4584db;
            padding: 1rem;
        }
        .logo{
           font-size: 30px; 
        }
    </style>
</head>
<body>
    
    <nav>
        <header>
    <div class="contenedor ">
                <nav>
                
                        <a href="../inicio_admin.php" class="logo">Liga</a>
                        <a href="añadir_equipos.php">Añadir Equipos</a>
                        <a href="equipos.php">Mirar equipos</a>
                        <a href="añadir_jugadores.php">Añadir Jugadores</a>
                        <a href="jugadores.php">Ver Jugadores</a>
                        <a href="clasificacion.php">Admin Clasificacion</a>
                    
                </nav>
            </div>
    </header>
    </nav>

    <div class="container">
        <h1>Registro de Puntos</h1>
        <form action="" method="POST" class="form-container">
            <label for="id_equipo">Selecciona el Equipo:</label>
            <select name="id_equipo" required>
                <option value="">Seleccione un equipo</option>
                <?php if ($resultado_equipos->num_rows > 0): ?>
                    <?php while ($equipo = $resultado_equipos->fetch_assoc()): ?>
                        <option value="<?php echo $equipo['id_equipo']; ?>"><?php echo htmlspecialchars($equipo['nombre']); ?></option>
                    <?php endwhile; ?>
                <?php else: ?>
                    <option value="">No hay equipos registrados.</option>
                <?php endif; ?>
            </select>

            <label for="partidos_ganados">Número de Partidos Ganados:</label>
            <input type="number" name="partidos_ganados" required>

            <label for="partidos_empatados">Número de Partidos Empatados:</label>
            <input type="number" name="partidos_empatados" required>

            <label for="partidos_perdidos">Número de Partidos Perdidos:</label>
            <input type="number" name="partidos_perdidos" required>

            <button type="submit">Almacenar Puntos</button>
        </form>

        <h2>Clasificación</h2>
        <table>
            <thead>
                <tr>
                    <th>Equipo</th>
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
    </div>
</body>
</html>

<?php
// Cerrar la conexión
$conexion->close();
?>
