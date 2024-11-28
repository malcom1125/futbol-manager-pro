<?php
// Iniciamos la sesión para poder usar variables de sesión
session_start();

// Inicializamos las variables del mensaje para evitar el error "Undefined variable"
$mensaje = '';
$tipo_mensaje = '';

// Configuración de la conexión a la base de datos
$host = "localhost";  // Cambia por tu host si es necesario
$usuario_bd = "root";  // Cambia por tu usuario de base de datos
$contrasena_bd = "";   // Cambia por tu contraseña de base de datos
$nombre_bd = "liga_db"; // Cambia por el nombre de tu base de datos

// Crear la conexión
$conexion = new mysqli($host, $usuario_bd, $contrasena_bd, $nombre_bd);

// Verificar si hay algún error en la conexión
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Comprobar si se ha enviado el formulario de inicio de sesión
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['nombre_usuario'];
    $contrasena = $_POST['contrasena'];

    // Consultar la base de datos para verificar el usuario y su rol
    $query = "SELECT rol, contrasena FROM usuarios WHERE nombre_usuario = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Verificar si la contraseña es correcta (no encriptada)
        if ($contrasena === $row['contrasena']) {
            // Guardar el nombre de usuario y rol en la sesión
            $_SESSION['usuario'] = $usuario;
            $_SESSION['rol'] = $row['rol']; // Almacena el rol en la sesión

            // Redirigir según el rol
            if ($row['rol'] == 'administrador') {
                $_SESSION['inicio_sesion_exitoso'] = "Inicio de sesión exitoso como administrador.";
                header("Location: inicio_admin.php");
                exit();
            } else {
                $_SESSION['inicio_sesion_exitoso'] = "Inicio de sesión exitoso como usuario.";
                header("Location: inicio.php");
                exit();
            }
        } else {
            // Contraseña incorrecta
            $mensaje = 'Nombre de usuario o contraseña incorrectos.';
            $tipo_mensaje = "error";
        }
    } else {
        // Usuario no encontrado
        $mensaje = 'Nombre de usuario o contraseña incorrectos.';
        $tipo_mensaje = "error";
    }
}

// Verificamos si hay algún mensaje de error o éxito en la sesión
if (isset($_SESSION['error_login'])) {
    $mensaje = $_SESSION['error_login'];
    $tipo_mensaje = "error";
    unset($_SESSION['error_login']); // Limpiamos el mensaje de la sesión
}

if (isset($_SESSION['inicio_sesion_exitoso'])) {
    $mensaje = $_SESSION['inicio_sesion_exitoso'];
    $tipo_mensaje = "success";
    unset($_SESSION['inicio_sesion_exitoso']); // Limpiamos el mensaje de la sesión
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liga - Iniciar Sesión</title>
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
        .register-link {
            text-align: center;
            margin-top: 1rem;
        }
        .register-link a {
            color: #2563eb;
            text-decoration: none;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
        /* Estilos del mensaje flotante */
        #mensaje-flotante {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px;
            background-color: #f44336; /* Rojo por defecto para errores */
            color: white;
            border-radius: 5px;
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.5s ease;
        }
        #mensaje-flotante.show {
            opacity: 1;
        }
        /* Diferentes estilos para los tipos de mensajes */
        #mensaje-flotante.success {
            background-color: #4CAF50; /* Verde para mensajes exitosos */
        }
    </style>
</head>
<body>
    <nav>
        <div class="container">
            <a href="inicio.php" class="logo">Liga</a>
        </div>
    </nav>
    <div class="container">
        <div class="form-container">
            <h2>Iniciar Sesión</h2>
            <form action="" method="POST"> <!-- El formulario se procesa en el mismo archivo -->
                <label for="username">Nombre de usuario:</label>
                <input type="text" name="nombre_usuario" placeholder="Nombre de usuario" required>
                
                <label for="password">Contraseña:</label>
                <input type="password" name="contrasena" placeholder="Contraseña" required>
                
                <button type="submit">Iniciar Sesión</button>
            </form>
            <div class="register-link">
                <p>¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p>
            </div>
        </div>
    </div>

    <!-- Mensaje flotante -->
    <?php if (!empty($mensaje)): ?>
        <div id="mensaje-flotante" class="<?php echo $tipo_mensaje; ?>">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <script>
        // Mostrar mensaje flotante si existe
        const mensajeFlotante = document.getElementById('mensaje-flotante');
        if (mensajeFlotante) {
            mensajeFlotante.classList.add('show');
            setTimeout(() => {
                mensajeFlotante.classList.remove('show');
            }, 5000); // Desaparece después de 5 segundos
        }
    </script>
</body>
</html>
