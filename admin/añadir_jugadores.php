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

// Inicializar variables
$mensaje = "";
$tipo_mensaje = "";

// Procesar el formulario si se envía
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $dorsal = $_POST['Dorsal'] ?? ''; // Asegúrate de que el nombre del campo sea correcto
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
    $posicion = $_POST['posicion'] ?? '';
    $id_equipo = $_POST['id_equipo'] ?? '';

    // Insertar jugador en la base de datos
    $stmt = $conexion->prepare("INSERT INTO jugadores (nombre, apellido, dorsal, fecha_nacimiento, posicion, id_equipo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssissi", $nombre, $apellido, $dorsal, $fecha_nacimiento, $posicion, $id_equipo);

    if ($stmt->execute()) {
        // Redirigir a la misma página para evitar reenvíos
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        // En caso de error, almacenar el mensaje
        $mensaje = "Error al registrar el jugador: " . $stmt->error;
    }

    $stmt->close();
}

// Consulta para obtener los equipos
$query = "SELECT id_equipo, nombre FROM equipos";
$resultado = $conexion->query($query);

if (!$resultado) {
    die("Error en la consulta: " . $conexion->error);
}

// Guardar los equipos en un array
$equipos = [];
while ($row = $resultado->fetch_assoc()) {
    $equipos[] = $row;
}

// Cerrar la conexión
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liga - Registro</title>
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
        .form-container {
            background-color: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            max-width: 400px;
            margin: 2rem auto;
        }
        h2 {
            color: #2563eb;
            margin-bottom: 1rem;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 1rem;
        }
        input, select {
            padding: 0.5rem;
            margin-top: 0.25rem;
            border: 1px solid #d1d5db;
            border-radius: 4px;
        }
        button {
            background-color: #2563eb;
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 4px;
            margin-top: 1.5rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #1d4ed8;
        }
        /* Mensaje flotante */
        #mensaje-flotante {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px;
            background-color: #4CAF50; /* Verde por defecto para éxito */
            color: white;
            border-radius: 5px;
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.5s ease;
        }
        #mensaje-flotante.error {
            background-color: #f44336; /* Rojo para errores */
        }
        #mensaje-flotante.show {
            opacity: 1;
        }
        .logo {
           font-size: 30px; 
        }
        footer {
            background-color: #4584db;
            color: white;
            text-align: center;
            padding: 1rem;
            margin-top: 20px;
        }
    </style>    
</head>
<body>
    <nav>
        <header>
        <div class="container">
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
        <?php if ($mensaje): ?>
            <div id="mensaje-flotante" class="<?php echo $tipo_mensaje; ?> show"><?php echo $mensaje; ?></div>
            <script>
                setTimeout(() => document.getElementById("mensaje-flotante").style.opacity = "0", 3000);
            </script>
        <?php endif; ?>

        <div class="form-container">
            <h2>Registro</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" required>

                <label for="apellido">Apellido</label>
                <input type="text" name="apellido" required>

                <label for="Dorsal">Dorsal</label>
                <input type="number" name="Dorsal" required>

                <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                <input type="date" name="fecha_nacimiento" required>

                <label for="posicion">Posición</label>
                <select name="posicion" required>
                    <option value="Portero">Portero</option>
                    <option value="Defensa">Defensa</option>
                    <option value="Centrocampista">Centrocampista</option>
                    <option value="Delantero">Delantero</option>
                    <option value="Banca">Banca</option>
                </select>
                
                <label for="id_equipo">Equipo</label>
                <select name="id_equipo" required>
                    <option value="" disabled selected>Selecciona un equipo</option>
                    <?php foreach ($equipos as $equipo): ?>
                        <option value="<?php echo $equipo['id_equipo']; ?>"><?php echo htmlspecialchars($equipo['nombre']); ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">Registrar Jugador</button>
            </form>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; prom 2024</p>
        </div>
    </footer>
</body>
</html>
