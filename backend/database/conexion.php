<?php
$host = "localhost";
$dbname = "registro_usuarios";
$username = "root";
$password = "";

// Crear conexión MySQLi
$conn = new mysqli($host, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}
?>