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

// Consultar todos los usuarios
$consulta_usuarios = "SELECT id, nombre_usuario, rol FROM usuarios"; // Cambiado 'id' a 'id_usuario'
$resultado_usuarios = $conexion->query($consulta_usuarios);

// Actualizar el rol 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_usuario'], $_POST['nuevo_rol'])) {
    $id_usuario = $_POST['id_usuario'];
    $nuevo_rol = $_POST['nuevo_rol'];

    // Usar declaraciones preparadas
    $stmt = $conexion->prepare("UPDATE usuarios SET rol = ? WHERE id = ?"); // Asegúrate de que el campo se llame 'id_usuario'
    $stmt->bind_param("si", $nuevo_rol, $id_usuario);
    $stmt->execute();
    $stmt->close();

    // Recargar la página para reflejar los cambios
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Liga - añadir equipo</title>
    <style>
        /* Estilos para el menú desplegable */
        .usuario-logueado {
            position: relative;
            display: inline-block;
        }

        .btn-usuario {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            cursor: pointer;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .usuario-logueado:hover .dropdown-content {
            display: block;
        }

        /* Mensaje flotante */
        #mensaje-flotante {
            position: fixed;
            top: 20px;
            right: 10px;
            height: 20px;
            padding: 15px;
            background-color: #2563eb; /* Azul para mensajes */
            color: white;
            border-radius: 5px;
            z-index: 1000;
            opacity: 0; /* Comienza oculto */
            transition: opacity 0.5s ease, transform 0.5s ease; /* Transición suave */
            transform: translateY(-20px); /* Mueve el mensaje un poco hacia arriba */
        }

        /* Clase para mostrar el mensaje */
        #mensaje-flotante.show {
            opacity: 1; /* Muestra el mensaje */
            transform: translateY(0); /* Regresa a la posición original */
        }

        /* Estilo para el mensaje de éxito */
        #mensaje-flotante.success {
            background-color: #4CAF50; /* Verde para mensajes exitosos */
        }

        /* Estilo para el mensaje de error */
        #mensaje-flotante.error {
            background-color: #f44336; /* Rojo para mensajes de error */
        }

        /* Estilos para el panel admin */
        .panel-admin {
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
        }
        .logo{
           font-size: 30px; 
        }

        /* Estilos ocultos para el panel de administración */
        .admin-panel {
            display: none;
        }
        
    </style>
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <div>
                    <a href="inicio_admin.php" class="logo">Liga</a>
                    <a href="admin/añadir_equipos.php">Añadir Equipos</a>
                    <a href="admin/equipos.php">Mirar equipos</a>
                    <a href="admin/añadir_jugadores.php">Añadir Jugadores</a>
                    <a href="admin/jugadores.php">Ver Jugadores</a>
                    <a href="admin/clasificacion.php">Admin Clasificacion</a>
                </div>
                <div>
                    <div class="usuario-logueado">
                        <span id="usuario-logueado-nombre">Usuario administrador</span>
                        <div class="dropdown-content" id="dropdown-menu">
                            <a href="cerrar_sesion.php">Cerrar Sesión</a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <div class="container">
        <main>
            <div class="content">
                <h1>Fútbol Manager Pro</h1>
                <p>Fútbol Manager Pro es una herramienta de gestión de equipos de fútbol diseñada para simplificar la administración de clubes, jugadores y partidos. Este software permite a los usuarios almacenar y gestionar información clave sobre los equipos, sus jugadores y los encuentros que disputan.
                <br><br>
                Características Principales:
                <br><br>
                1. Gestión de Equipos: Permite agregar, editar y eliminar equipos de fútbol, así como almacenar información relevante, como el nombre del equipo, el número de jugadores y el logo del club.
                <br><br>
                2. Administración de Jugadores: Los usuarios pueden registrar jugadores, incluyendo datos como nombre, apellido, fecha de nacimiento, posición y el equipo al que pertenecen. Esto facilita la organización y seguimiento del rendimiento de cada jugador.
                <br><br>
                3. Generación de Partidos: Fútbol Manager Pro genera aleatoriamente partidos entre los equipos registrados, asegurando que no se repitan encuentros anteriores. La interfaz muestra el nombre y el logo de cada equipo, así como un cuadro que indica "vs" para una visualización clara.
                <br><br>
                4. Interfaz Intuitiva: El diseño del software es amigable y fácil de usar, lo que permite a los administradores del club gestionar la información sin complicaciones.
                <br><br>
                5. Base de Datos Eficiente: Utiliza una base de datos MySQL para almacenar todos los datos de manera segura y eficiente, asegurando la integridad de la información.
                <br><br>
                Con Fútbol Manager Pro, los clubes de fútbol pueden gestionar de manera efectiva sus operaciones diarias, permitiendo que los administradores se concentren en lo que realmente importa: ¡el juego!</p>
            </div>
            <aside>
                <div class="widget">
                    <h3>Usuarios Registrados</h3>
                    <?php if ($resultado_usuarios->num_rows > 0): ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nombre de Usuario</th>
                                    <th>Rol</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($usuario = $resultado_usuarios->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($usuario['nombre_usuario']); ?></td>
                                        <td><?php echo htmlspecialchars($usuario['rol']); ?></td>
                                        <td>
                                            <form action="" method="POST">
                                                <input type="hidden" name="id_usuario" value="<?php echo $usuario['id']; ?>"> <!-- Cambiado aquí -->
                                                <select name="nuevo_rol" onchange="this.form.submit()">
                                                    <option value="usuario" <?php echo $usuario['rol'] === 'usuario' ? 'selected' : ''; ?>>Usuario</option>
                                                    <option value="administrador" <?php echo $usuario['rol'] === 'administrador' ? 'selected' : ''; ?>>Administrador</option>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No hay usuarios registrados.</p>
                    <?php endif; ?>
                </div>
            </aside>
        </main>
    </div>

    <footer>
        <div class="container">
            <p>&copy; Liga 2024</p>
        </div>
    </footer>
</body>
</html>
