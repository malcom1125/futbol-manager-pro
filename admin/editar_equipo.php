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
$consulta = "SELECT id_equipo, nombre, ciudad FROM equipos";
$resultado = $conexion->query($consulta);

// Verificar si la consulta fue exitosa
if (!$resultado) {
    die("Error en la consulta: " . $conexion->error);
}

session_start(); // Iniciar la sesión

// Variables iniciales
$nombre = '';
$ciudad = '';
$fecha_fundacion = '';
$estadio = '';

// Verificar si se ha pasado el ID a través de GET
if (isset($_GET['id_equipo'])) {
    $id = $_GET['id_equipo'];
    $query = "SELECT * FROM equipos WHERE id_equipo = ?";
    $stmt = $conexion->prepare($query);  // Usar $conexion en lugar de $conn
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Si existe un equipo con ese ID, obtenemos los datos
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $nombre = $row['nombre'];
        $ciudad = $row['ciudad'];
        $fecha_fundacion = $row['fecha_fundacion'];
        $estadio = $row['estadio'];
    } else {
        // Redirigir si el equipo no existe
        header('Location: añadir_equipos.php');
        exit();
    }
} else {
    // Redirigir si no se ha pasado el ID
    header('Location: añadir_equipos.php');
    exit();
}

// Verificar si el formulario fue enviado para actualizar el equipo
if (isset($_POST['update'])) {
    if (isset($_GET['id_equipo'])) {
        $id = $_GET['id_equipo']; // Asegurarse de obtener el ID correctamente
        $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
        $ciudad = mysqli_real_escape_string($conexion, $_POST['ciudad']);
        $fecha_fundacion = mysqli_real_escape_string($conexion, $_POST['fecha_fundacion']);
        $estadio = mysqli_real_escape_string($conexion, $_POST['estadio']);

        // Actualizar el equipo
        $query = "UPDATE equipos SET nombre = ?, ciudad = ?, fecha_fundacion = ?, estadio = ? WHERE id_equipo = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param('ssssi', $nombre, $ciudad, $fecha_fundacion, $estadio, $id);
        if ($stmt->execute()) {
            $_SESSION['message'] = 'Equipo actualizado correctamente';
            $_SESSION['message_type'] = 'success';
            header('Location: equipos.php');  // Redirigir a equipos.php después de la actualización
            exit();
        } else {
            $_SESSION['message'] = 'Error al actualizar el equipo: ' . $stmt->error;
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
    <title>Editar Equipo - Liga</title>
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
        input {
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

                /* Estilos para la tabla */
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
            <h2>Editar Equipo</h2>
            <form action="editar_equipo.php?id_equipo=<?php echo $id; ?>" method="POST">
                <label for="nombre">Nombre del Equipo:</label>
                <input name="nombre" type="text" value="<?php echo htmlspecialchars($nombre); ?>" placeholder="Actualizar Nombre" required>

                <label for="ciudad">Ciudad:</label>
                <input name="ciudad" type="text" value="<?php echo htmlspecialchars($ciudad); ?>" placeholder="Actualizar Ciudad" required>

                <label for="fecha_fundacion">Fecha de Fundación:</label>
                <input name="fecha_fundacion" type="date" value="<?php echo htmlspecialchars($fecha_fundacion); ?>" required>

                <label for="estadio">Estadio:</label>
                <input name="estadio" type="text" value="<?php echo htmlspecialchars($estadio); ?>" placeholder="Actualizar Estadio" required>

                <button type="submit" name="update">Actualizar</button>
            </form>
        </div>
    </div>
</body>
</html>
