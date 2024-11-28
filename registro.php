<!DOCTYPE html>
<html lang="es">
<head>
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
            <h2>Registro</h2>
            <form action="procesar_registro.php" method="POST">
                <label for="nombre">Nombre completo:</label>
                <input type="text" id="nombre_completo" placeholder="nombre completo" name="nombre_completo" required>
                
                <label for="email">Correo electrónico:</label>
                <input type="email" name="correo_electronico" id="correo_electronico " placeholder="correo electronico" name="correo_electronico " required>
                
                <label for="username">Nombre de usuario:</label>
                <input type="text" id="nombre_usuario" placeholder="nombre de usuario" name="nombre_usuario" required>
                
                <label for="password">Contraseña:</label>
                <input type="password" id="contrasena" placeholder="contraseña" name="contrasena" required>
                
                
                <button type="submit">Registrarse</button>
            </form>
        </div>
    </div>
</body>
</html>