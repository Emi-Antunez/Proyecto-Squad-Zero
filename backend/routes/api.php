<?php
require "../controllers/productos.php"; // Importar el controlador que maneja la lógica de negocio para productos

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Obtener el método de la solicitud HTTP (GET, POST, PUT, DELETE)
$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod == "GET") {
    if (isset($_GET['id'])) {
        mostrarProducto($_GET['id']); // Mostrar un producto específico
    } else {
        listarProductos(); // Listar todos los productos
    }
} 
elseif ($requestMethod == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    agregarProducto($data['nombre'], $data['descripcion'], $data['precio'], $data['stock'], $data['imagen']); // Agregar un nuevo producto, stock es opcional
} 
elseif ($requestMethod == "PUT") {
    $data = json_decode(file_get_contents("php://input"), true);
    modificarProducto($data['id'], $data['nombre'], $data['descripcion'], $data['precio'], $data['stock'], $data['imagen']); // Modificar un producto existente, stock es opcional
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