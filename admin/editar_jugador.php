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

// Consulta para obtener los equipos
$consulta_equipos = "SELECT id_equipo, nombre FROM equipos";
$resultado_equipos = $conexion->query($consulta_equipos);

// Verificar si la consulta fue exitosa
if (!$resultado_equipos) {
    die("Error en la consulta: " . $conexion->error);
}

session_start(); // Iniciar la sesión

// Variables iniciales
$nombre = '';
$apellido = '';
$fecha_nacimiento = '';
$posicion = '';
$dorsal = '';
$id_equipo = '';

// Verificar si se ha pasado el ID a través de GET
if (isset($_GET['id_jugador'])) {
    $id = $_GET['id_jugador'];
    $query = "SELECT * FROM jugadores WHERE id_jugador = ?";
    $stmt = $conexion->prepare($query); 
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Si existe un jugador con ese ID, obtenemos los datos
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $nombre = $row['nombre'];
        $apellido = $row['apellido'];
        $fecha_nacimiento = $row['fecha_nacimiento'];
        $posicion = $row['posicion'];
        $dorsal = $row['dorsal'];
        $id_equipo = $row['id_equipo'];
    } else {
        // Redirigir si el jugador no existe
        header('Location: añadir_jugador.php');
        exit();
    }
} else {
    // Redirigir si no se ha pasado el ID
    header('Location: añadir_jugador.php');
    exit();
}

// Verificar si el formulario fue enviado para actualizar el jugador
if (isset($_POST['update'])) {
    if (isset($_GET['id_jugador'])) {
        $id = $_GET['id_jugador'];
        $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
        $apellido = mysqli_real_escape_string($conexion, $_POST['apellido']);
        $fecha_nacimiento = mysqli_real_escape_string($conexion, $_POST['fecha_nacimiento']);
        $posicion = mysqli_real_escape_string($conexion, $_POST['posicion']);
        $dorsal = mysqli_real_escape_string($conexion, $_POST['dorsal']);
        $id_equipo = mysqli_real_escape_string($conexion, $_POST['id_equipo']);

        // Actualizar el jugador
        $query = "UPDATE jugadores SET nombre = ?, apellido = ?, fecha_nacimiento = ?, posicion = ?, dorsal = ?, id_equipo = ? WHERE id_jugador = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param('ssssiii', $nombre, $apellido, $fecha_nacimiento, $posicion, $dorsal, $id_equipo, $id);
        if ($stmt->execute()) {
            $_SESSION['message'] = 'Jugador actualizado correctamente';
            $_SESSION['message_type'] = 'success';
            header('Location: jugadores.php');  // Redirigir a jugadores.php después de la actualización
            exit();
        } else {
            $_SESSION['message'] = 'Error al actualizar el jugador: ' . $stmt->error;
            $_SESSION['message_type'] = 'danger';
        }
    }
}

// Cerrar la conexión al final
$conexion->close();
?>

    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Editar Jugador - Liga</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f0f4f8;
            }.logo{
           font-size: 30px; 
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
            <div class="form-container">
                <h2>Editar Jugador</h2>
                <form action="editar_jugador.php?id_jugador=<?php echo $id; ?>" method="POST">
                    <label for="nombre">Nombre:</label>
                    <input name="nombre" type="text" value="<?php echo htmlspecialchars($nombre); ?>" placeholder="Actualizar Nombre" required>

                    <label for="apellido">Apellido:</label>
                    <input name="apellido" type="text" value="<?php echo htmlspecialchars($apellido); ?>" placeholder="Actualizar Apellido" required>

                    <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                    <input name="fecha_nacimiento" type="date" value="<?php echo htmlspecialchars($fecha_nacimiento); ?>" required>

                    <label for="posicion">Posición:</label>
                    <select name="posicion" required>
                        <option value="Portero" <?php if($posicion == 'Portero') echo 'selected'; ?>>Portero</option>
                        <option value="Defensa" <?php if($posicion == 'Defensa') echo 'selected'; ?>>Defensa</option>
                        <option value="Mediocampista" <?php if($posicion == 'Mediocampista') echo 'selected'; ?>>Mediocampista</option>
                        <option value="Delantero" <?php if($posicion == 'Delantero') echo 'selected'; ?>>Delantero</option>
                    </select>

                    <label for="dorsal">Dorsal:</label>
                    <input name="dorsal" type="number" value="<?php echo htmlspecialchars($dorsal); ?>" placeholder="Actualizar Dorsal" required>

                    <label for="id_equipo">Equipo:</label>
                    <select name="id_equipo" required>
                        <?php while ($equipo = $resultado_equipos->fetch_assoc()): ?>
                            <option value="<?php echo $equipo['id_equipo']; ?>" <?php if ($equipo['id_equipo'] == $id_equipo) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($equipo['nombre']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <button type="submit" name="update">Actualizar</button>
                </form>
            </div>
        </div>
    </body>
    </html>
