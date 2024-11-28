<?php
require 'conexion.php'; // Incluye la conexión a la base de datos
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
    $ciudad = isset($_POST['ciudad']) ? $_POST['ciudad'] : null;
    $fecha_fundacion = isset($_POST['fecha_fundacion']) ? $_POST['fecha_fundacion'] : null;
    $estadio = isset($_POST['estadio']) ? $_POST['estadio'] : null;

    // Verificar si todos los campos están completos
    if (!empty($nombre) && !empty($ciudad) && !empty($fecha_fundacion) && !empty($estadio)) {
        try {
            // Prepara e inserta los datos en la tabla 'equipos'
            $stmt = $pdo->prepare("INSERT INTO equipos (nombre, ciudad, fecha_fundacion, estadio) VALUES (:nombre, :ciudad, :fecha_fundacion, :estadio)");
            $stmt->execute([
                ':nombre' => $nombre, 
                ':ciudad' => $ciudad, 
                ':fecha_fundacion' => $fecha_fundacion, 
                ':estadio' => $estadio
            ]);

            // Mensaje de éxito
            $_SESSION['registro_exitoso'] = "Equipo registrado con éxito"; 
            
            // Redirigir a la página de añadir equipos para mostrar el mensaje flotante
            header("Location: añadir_equipos.php");
            exit();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $_SESSION['error_registro'] = "El equipo ya está registrado"; 
            } else {
                $_SESSION['error_registro'] = "Error al registrar el equipo: " . $e->getMessage();
            }
            header("Location: añadir_equipos.php");
            exit();
        }
    } else {
        // Error si faltan campos
        $_SESSION['error_registro'] = "Todos los campos son obligatorios.";
        header("Location: añadir_equipos.php");
        exit();
    }
}
?>
