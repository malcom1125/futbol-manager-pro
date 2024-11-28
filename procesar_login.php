<?php
// Iniciar la sesión
session_start();

// Configuración de la conexión a la base de datos
$host = "localhost";  // Cambia por tu host si es diferente
$usuario = "root";     // Cambia por tu usuario de base de datos
$contrasena_bd = "";   // Cambia por tu contraseña de base de datos
$nombre_bd = "liga_db"; // Cambia por el nombre de tu base de datos

// Crear la conexión
$conexion = new mysqli($host, $usuario, $contrasena_bd, $nombre_bd);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Verificar si se recibieron los datos del formulario
$username = isset($_POST['nombre_usuario']) ? $conexion->real_escape_string($_POST['nombre_usuario']) : '';
$contrasena = isset($_POST['contrasena']) ? $_POST['contrasena'] : '';

// Verificar si los campos no están vacíos
if (!empty($username) && !empty($contrasena)) {
    // Preparar la consulta SQL para buscar el usuario por nombre de usuario
    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE nombre_usuario = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    // Verificar si el usuario existe
    if ($usuario) {
        // Comparación directa de la contraseña (se recomienda usar hashing)
        if ($contrasena === $usuario['contrasena']) {
            // Almacenar el nombre de usuario y el rol en la sesión
            $_SESSION['usuario'] = $usuario['nombre_usuario'];
            $_SESSION['rol'] = $usuario['rol'];

            // Redirigir según el rol del usuario
            if ($usuario['rol'] === 'administrador') {
                header("Location: admin/inicio_admin.php");
                exit();
            } elseif ($usuario['rol'] === 'usuario') {
                header("Location: inicio.php");
                exit();
            } else {
                $_SESSION['error_login'] = "Rol no reconocido.";
            }
        } else {
            // Contraseña incorrecta
            $_SESSION['error_login'] = "Usuario o contraseña incorrectos.";
        }
    } else {
        // Usuario no encontrado
        $_SESSION['error_login'] = "Usuario o contraseña incorrectos.";
    }
} else {
    // Campos vacíos
    $_SESSION['error_login'] = "Todos los campos son obligatorios.";
}

// Verificar si hay algún mensaje de error o éxito
if (isset($_SESSION['error_login'])) {
    $mensaje = $_SESSION['error_login'];
    $tipo_mensaje = "error";
    unset($_SESSION['error_login']); // Limpiar el mensaje de error
}

if (isset($_SESSION['inicio_sesion_exitoso'])) {
    $mensaje = $_SESSION['inicio_sesion_exitoso'];
    $tipo_mensaje = "success";
    unset($_SESSION['inicio_sesion_exitoso']); // Limpiar el mensaje de éxito
}

// Cerrar la conexión
$conexion->close();
?>
