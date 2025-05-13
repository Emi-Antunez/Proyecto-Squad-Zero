<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require "../controllers/usuarios.php"; // Solo importamos el controlador de usuarios

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod == "GET") {
    $solicitud = $_GET["url"];

    if ($solicitud == "usuarios") {
        if (isset($_GET["accion"]) && $_GET["accion"] == "eliminar") {
            eliminarUsuarios(); // Eliminar un usuario
        } else {
            obtenerUsuarios(); // Obtener todos los usuarios
        }
    }
}

elseif ($requestMethod == "POST") {
    if (isset($_POST["nombre"]) && isset($_POST["apellido"]) && isset($_POST["gmail"]) && isset($_POST["usuario"]) && isset($_POST["contrasena"])) {
        // Agregar un usuario
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $gmail = $_POST["gmail"];
        $usuario = $_POST["usuario"];
        $contrasena = $_POST["contrasena"];
        agregarUsuario($nombre, $apellido, $gmail, $usuario, $contrasena);
    }
}

else {
    echo json_encode(["error" => "Método no permitido"]);
}
