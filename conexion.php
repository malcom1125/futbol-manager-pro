<?php
// Configuración de conexión
$host = 'localhost';
$dbname = 'liga_db';
$user = 'root';
$pass = '';

try {
    // Conexión usando PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión con PDO: " . $e->getMessage());
}

// Crear conexión
$conn = new mysqli($host, $user, $pass, $dbname);

// Conexión usando mysqli
$conexion = new mysqli($host, $user, $pass, $dbname);

if ($conexion->connect_error) {
    die("Error en la conexión con MySQLi: " . $conexion->connect_error);
}
?>
