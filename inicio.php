<?php
session_start(); // Iniciar la sesión para comprobar si el usuario está logueado

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

// Función para verificar si el usuario es administrador
function esAdmin($conexion, $usuario) {
    $query = "SELECT rol FROM usuarios WHERE nombre_usuario = ?";
    $stmt = $conexion->prepare($query);
    
    if (!$stmt) {
        die("Error en la consulta: " . $conexion->error);
    }
    
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        return $row['rol'] == 'admin';
    }
    return false;
}

$esAdmin = false;
if (isset($_SESSION['usuario'])) {
    $esAdmin = esAdmin($conexion, $_SESSION['usuario']);
}


// Obtener la clasificación
$query_clasificacion = "SELECT e.nombre, c.puntos
                        FROM clasificacion c
                        JOIN equipos e ON c.id_equipo = e.id_equipo
                        ORDER BY c.puntos DESC";

$stmt_clasificacion = $conexion->prepare($query_clasificacion);

if (!$stmt_clasificacion) {
    die("Error en la consulta de clasificación: " . $conexion->error);
}

$stmt_clasificacion->execute();
$result_clasificacion = $stmt_clasificacion->get_result();

// Asegúrate de cerrar los statements si ya no los necesitas
$stmt_clasificacion->close();
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Liga - Página Principal</title>
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
            right: 20px;
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
    </style>
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
                    <?php if ($esAdmin): ?>
                        <a href="admin_panel.php">Panel de Administración</a>
                    <?php endif; ?>
                </div>
                <div>
                    <?php if (isset($_SESSION['usuario'])): ?>
                        <div class="usuario-logueado">
                            <span id="usuario-logueado-nombre"><?= htmlspecialchars($_SESSION['usuario']); ?></span>
                            <div class="dropdown-content" id="dropdown-menu">
                                <?php if ($esAdmin): ?>
                                    <a href="admin_panel.php">Panel de Administración</a>
                                <?php endif; ?>
                                <a href="cerrar_sesion.php">Cerrar Sesión</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="login.php">Iniciar Sesión</a>
                        <a href="registro.php">Registrarse</a>
                    <?php endif; ?>
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
                    <h3>Clasificación</h3>
                    <ul>
                        <?php while ($clasificacion = $result_clasificacion->fetch_assoc()): ?>
                            <li>
                                <?= htmlspecialchars($clasificacion['nombre']) ?> - <?= htmlspecialchars($clasificacion['puntos']) ?> puntos
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </aside>
        </main>
    </div>

    <!-- Mostrar el mensaje flotante si el registro fue exitoso -->
    <?php
    if (isset($_SESSION['registro_exitoso'])) {
        $mensaje = $_SESSION['registro_exitoso'];
        unset($_SESSION['registro_exitoso']); // Eliminar el mensaje para que no se muestre nuevamente
    } else {
        $mensaje = null;
    }
    ?>

    <?php if ($mensaje): ?>
        <div id="mensaje-flotante" class="show"><?= htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>

    <script>
        // Mostrar el mensaje flotante si existe
        const mensajeFlotante = document.getElementById('mensaje-flotante');
        if (mensajeFlotante) {
            mensajeFlotante.classList.add('show');

            // Desvanecer el mensaje después de 4 segundos
            setTimeout(() => {
                mensajeFlotante.classList.remove('show');
            }, 4000); // Tiempo en milisegundos
        }

        // Mostrar/ocultar el menú desplegable al hacer clic en el nombre del usuario
        document.getElementById('usuario-logueado-nombre')?.addEventListener('click', function() {
            const dropdown = document.getElementById('dropdown-menu');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        });

        // Cerrar el menú si se hace clic fuera de él
        window.onclick = function(event) {
            if (!event.target.matches('#usuario-logueado-nombre')) {
                const dropdowns = document.querySelectorAll('.dropdown-content');
                dropdowns.forEach(function(dropdown) {
                    dropdown.style.display = 'none';
                });
            }
        };
    </script>

    <footer>
        <div class="container">
            <p>&copy; 2024 Prom 2024.</p>
        </div>
    </footer>
</body>
</html>
