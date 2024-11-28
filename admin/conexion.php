<?php
$host = 'localhost'; // Cambia según tu configuración
$db = 'liga_db'; // Nombre de tu base de datos
$user = 'root'; // Usuario de tu base de datos
$pass = ''; // Contraseña de tu base de datos

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}

?>
