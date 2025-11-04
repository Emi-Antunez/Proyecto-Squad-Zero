<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

session_start();

require "../controllers/reservas.php";
require "../controllers/usuarios.php";
require "../controllers/publicaciones.php";

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('log_errors', 1);
ini_set("error_log", "./errores.log"); 

$requestMethod = $_SERVER["REQUEST_METHOD"];
$action = isset($_GET['action']) ? $_GET['action'] : null;

if ($action === "login" && $requestMethod == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    loginUsuario($data['usuario'], $data['contrasena']);
}
elseif ($action === "register" && $requestMethod == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    agregarUsuario($data['nombre'], $data['apellido'], $data['gmail'], $data['usuario'], $data['contrasena']);
}
elseif ($action === "usuarios" && $requestMethod == "GET") {
    listarUsuarios();
}
elseif ($action === "getUsuarios" && $requestMethod == "GET") {
    listarUsuarios();
}
elseif ($action === "cambiarRol" && $requestMethod == "PUT") {
    $data = json_decode(file_get_contents("php://input"), true);
    cambiarRolUsuario($data['id'], $data['rol']);
}
elseif ($action === "eliminarUsuario" && $requestMethod == "DELETE" && isset($_GET['id'])) {
    eliminarUsuario($_GET['id']);
}
elseif ($action === "usuarios" && $requestMethod == "DELETE" && isset($_GET['id'])) {
    eliminarUsuario($_GET['id']);
}
// Publicaciones
elseif ($action === "publicaciones" && $requestMethod == "GET") {
    if (isset($_GET['id'])) {
        mostrarPublicacion($_GET['id']);
    } else {
        listarPublicaciones();
    }
}
elseif ($action === "publicaciones" && $requestMethod == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    agregarPublicacion($data['titulo'], $data['descripcion'], $data['imagen'], $data['fecha']);
}
elseif ($action === "publicaciones" && $requestMethod == "PUT") {
    $data = json_decode(file_get_contents("php://input"), true);
    modificarPublicacion($data['id'], $data['titulo'], $data['descripcion'], $data['imagen'], $data['fecha']);
}
elseif ($action === "publicaciones" && $requestMethod == "DELETE" && isset($_GET['id'])) {
    eliminarPublicacion($_GET['id']);
}
elseif ($action === "upload-imagen" && $requestMethod == "POST") {
    subirImagenPublicacion();
}
// Reservas
elseif ($action === "getReservas" && $requestMethod == "GET") {
    listarReservas();
}
elseif ($action === "deleteReserva" && $requestMethod == "DELETE" && isset($_GET['id'])) {
    $data = json_decode(file_get_contents("php://input"), true);
    $motivo = isset($data['motivo_cancelacion']) ? $data['motivo_cancelacion'] : '';
    eliminarReserva($_GET['id'], $motivo);
}
elseif ($requestMethod == "GET") {
    if (isset($_GET['id'])) {
        mostrarReserva($_GET['id']);
    } else {
        listarReservas();
    }
}
elseif ($requestMethod == "POST") {
    if (!isset($_SESSION['id_usuario'])) {
        echo json_encode(["error" => "Debes iniciar sesión para reservar"]);
        exit;
    }
    $data = json_decode(file_get_contents("php://input"), true);
    agregarReserva(
        $_SESSION['id_usuario'],
        $data['tour'],
        $data['fecha'],
        $data['hora'],
        $data['cantidad_personas']
    );
}
elseif ($requestMethod == "PUT") {
    if (!isset($_SESSION['id_usuario'])) {
        echo json_encode(["error" => "Debes iniciar sesión para modificar reservas"]);
        exit;
    }
    $data = json_decode(file_get_contents("php://input"), true);
    modificarReserva(
        $data['id'],
        $_SESSION['id_usuario'],
        $data['tour'],
        $data['fecha'],
        $data['hora'],
        $data['cantidad_personas']
    );
}
elseif ($requestMethod == "DELETE") {
    if (isset($_GET['id'])) {
        // Obtener el motivo de cancelación del body si existe
        $data = json_decode(file_get_contents("php://input"), true);
        $motivo = isset($data['motivo_cancelacion']) ? $data['motivo_cancelacion'] : '';
        
        eliminarReserva($_GET['id'], $motivo);
    } else {
        echo json_encode(["error" => "Falta el parámetro id para eliminar"]);
    }
}
else {
    echo json_encode(["error" => "Método no permitido"]);
}
?>