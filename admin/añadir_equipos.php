<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
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
header nav a {
    color: white;
    text-decoration: none;
    font-size: 16px;
    margin-right: 20px;
    font-weight: 500;
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
                    <h2>Registro</h2>
                    <form action="procesar_registro_equipos.php" method="POST">
                        <label for="nombre">Nombre del Equipo:</label>
                        <input type="text" name="nombre" required>

                        <label for="ciudad">Ciudad:</label>
                        <input type="text" name="ciudad" required>

                        <label for="fecha_fundacion">Fecha de Fundación:</label>
                        <input type="date" name="fecha_fundacion" required>

                        <label for="estadio">Estadio:</label>
                        <input type="text" name="estadio" required>

                        <button type="submit">Registrar Equipo</button>
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