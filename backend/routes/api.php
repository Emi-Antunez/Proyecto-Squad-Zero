<?php

require "../controllers/productos.php";
require "../controllers/usuarios.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
elseif ($action === "usuarios" && $requestMethod == "DELETE" && isset($_GET['id'])) {
    eliminarUsuario($_GET['id']);
}
// Productos
elseif ($requestMethod == "GET") {
    if (isset($_GET['id'])) {
        mostrarProducto($_GET['id']);
    } else {
        listarProductos();
    }
}
elseif ($requestMethod == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    agregarProducto($data['nombre'], $data['descripcion'], $data['precio'], $data['stock'], $data['imagen']);
}
elseif ($requestMethod == "PUT") {
    $data = json_decode(file_get_contents("php://input"), true);
    modificarProducto($data['id'], $data['nombre'], $data['descripcion'], $data['precio'], $data['stock'], $data['imagen']);
}
elseif ($requestMethod == "DELETE") {
    if (isset($_GET['id'])) {
        eliminarProducto($_GET['id']);
    } else {
        echo json_encode(["error" => "Falta el parámetro id para eliminar"]);
    }
}
else {
    echo json_encode(["error" => "Método no permitido"]);
}
?>