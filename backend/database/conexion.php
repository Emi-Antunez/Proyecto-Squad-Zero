<?php
// ...existing code...
$host = "localhost";
$username = "root";
$password = "";
$database = "registro_usuarios"; // Cambia por el nombre de tu base de datos local

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    error_log("Error al conectar a la base de datos: " . mysqli_connect_error());
    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => 'Error al conectar a la base de datos']);
    }
    exit;
}

mysqli_set_charset($conn, 'utf8mb4');

// ...existing code...
?>