<?php
$host = "localhost";
$usuario = "root";
$contrasena_bd = "";
$nombre_bd = "liga_db";

// Conectar a la base de datos
$conexion = new mysqli($host, $usuario, $contrasena_bd, $nombre_bd);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Realiza la consulta para obtener los equipos
$query = "SELECT id_equipo, nombre, ciudad FROM equipos";
$resultado = $conexion->query($query);

// Verifica si la consulta se ejecutó correctamente
if (!$resultado) {
    die("Error en la consulta: " . $conexion->error);
}

// Manejar la solicitud de eliminación
if (isset($_GET['id_equipo'])) {
    $id_equipo = (int)$_GET['id_equipo'];

    // Iniciar transacción
    $conexion->begin_transaction();

    try {
        // Eliminar los jugadores relacionados al equipo
        $stmt = $conexion->prepare("DELETE FROM jugadores WHERE id_equipo = ?");
        $stmt->bind_param("i", $id_equipo);
        $stmt->execute();
        $stmt->close();

        // Eliminar el equipo de la tabla de clasificación
        $stmt = $conexion->prepare("DELETE FROM clasificacion WHERE id_equipo = ?");
        $stmt->bind_param("i", $id_equipo);
        $stmt->execute();
        $stmt->close();

        // Eliminar el equipo de la tabla equipos
        $stmt = $conexion->prepare("DELETE FROM equipos WHERE id_equipo = ?");
        $stmt->bind_param("i", $id_equipo);
        if ($stmt->execute()) {
            // Confirmar la transacción
            $conexion->commit();
            echo "<script>alert('Equipo, jugadores y clasificación eliminados exitosamente.');</script>";
        } else {
            throw new Exception('Error al eliminar el equipo.');
        }
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conexion->rollback();
        echo "<script>alert('" . $e->getMessage() . "');</script>";
    }

    $stmt->close();

    // Redirigir después de eliminar
    header("Location: equipos.php");
    exit();
}

// Cerrar la conexión
$conexion->close();
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liga - Equipos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f4f8;
        }
        .logo{
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
        /* Botones de acciones */
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
            padding: 10px 15px; /* Espaciado interno */
            border: none; /* Sin borde */
            border-radius: 5px; /* Bordes redondeados */
            font-size: 14px; /* Tamaño de fuente */
            cursor: pointer; /* Cambia el cursor al pasar el mouse */
            transition: background-color 0.3s ease; /* Efecto de transición */
            text-decoration: none;
        }

        /* Estilo para el botón de editar */
        .btn-editar {
            background-color: #007bff; /* Color de fondo */
            color: white; /* Color del texto */
        }

        .btn-editar:hover {
            background-color: #0056b3; /* Color al pasar el mouse */
        }

        /* Estilo para el botón de eliminar */
        .btn-eliminar {
            background-color: #dc3545; /* Color de fondo */
            color: white; /* Color del texto */
        }

        .btn-eliminar:hover {
            background-color: #c82333; /* Color al pasar el mouse */
        }

        /* Estilo para los iconos en los botones */
        .btn i {
            margin-right: 5px; /* Espaciado entre el icono y el texto */
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
    <h2>Equipos Registrados</h2>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message_type']; ?>">
            <?= $_SESSION['message']; ?>
            <?php unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>
    <table class="table">
        <thead>
            <tr>
                <th>ID Equipo</th>
                <th>Nombre</th>
                <th>Ciudad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Verifica si hay resultados antes de intentar acceder a ellos
            if ($resultado->num_rows > 0) {
                while ($row = $resultado->fetch_assoc()) {
            ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id_equipo']); ?></td>
                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                <td><?php echo htmlspecialchars($row['ciudad']); ?></td>
                <td>
                <div class="btn-group">
                    <a href="editar_equipo.php?id_equipo=<?php echo $row['id_equipo']; ?>" class="btn btn-editar">
                        <i class="fas fa-marker"></i> Editar
                    </a>
                    <a href="?id_equipo=<?php echo $row['id_equipo']; ?>" class="btn btn-eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar este equipo?');">
                        <i class="far fa-trash-alt"></i> Eliminar
                    </a>
                </div>
                </td>
            </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='4'>No hay equipos disponibles.</td></tr>";
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
