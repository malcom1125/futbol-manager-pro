<?php
// Incluir archivo de conexión
include("conexion.php");

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener y limpiar datos del formulario
    $nombre_completo = mysqli_real_escape_string($conexion, $_POST['nombre_completo']);
    $correo_electronico = mysqli_real_escape_string($conexion, $_POST['correo_electronico']);
    $nombre_usuario = mysqli_real_escape_string($conexion, $_POST['nombre_usuario']);
    $contrasena = mysqli_real_escape_string($conexion, $_POST['contrasena']);


    // Preparar la consulta de inserción
    $query = "INSERT INTO usuarios (nombre_usuario, correo_electronico, contrasena, nombre_completo) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    
    // Verificar si la preparación fue exitosa
    if ($stmt) {
        // Enlazar parámetros
        $stmt->bind_param('ssss', $nombre_usuario, $correo_electronico, $contrasena, $nombre_completo);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Redirigir a la página de inicio después de un registro exitoso
            header("Location: inicio.php");
            exit();
        } else {
            echo "Error al registrar el usuario: " . $stmt->error;
        }
        
        // Cerrar el statement
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conexion->error;
    }
}

// Cerrar conexión
$conexion->close();
?>
