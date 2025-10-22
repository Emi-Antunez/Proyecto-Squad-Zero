<?php


$host = "sql305.infinityfree.com"; // reemplazá con el host real
$username = "if0_40224467";        // tu usuario de MySQL
$password = "Emiemiemi524";        // la contraseña de tu cuenta
$database = "if0_40224467_registro_usuarios"; // tu base exacta

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


?>
// ...existing code...