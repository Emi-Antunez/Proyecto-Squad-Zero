<?php
// Controlador para manejar productos genéricos
require_once __DIR__ . '/../models/producto.php';


$productoModel = new Producto($conn); // Instancia del modelo

function listarProductos() {
    global $productoModel;
    echo json_encode($productoModel->obtenerTodos());
}

function mostrarProducto($id) {
    global $productoModel;
    $producto = $productoModel->obtenerPorId($id);
    if ($producto) {
        echo json_encode($producto);
    } else {
        echo json_encode(["error" => "Producto no encontrado"]);
    }
}

function agregarProducto($nombre, $descripcion, $precio, $stock, $imagen) {
    global $productoModel;
    if ($productoModel->agregar($nombre, $descripcion, $precio, $stock, $imagen)) {
        echo json_encode(["mensaje" => "Producto agregado"]);
    } else {
        echo json_encode(["error" => "No se pudo agregar"]);
    }
}

function modificarProducto($id, $nombre, $descripcion, $precio, $stock, $imagen) {
    global $productoModel;
    if ($productoModel->modificar($id, $nombre, $descripcion, $precio, $stock, $imagen)) {
        echo json_encode(["mensaje" => "Producto modificado", "id" => $id]);
    } else {
        echo json_encode(["error" => "No se pudo modificar"]);
    }
}

function eliminarProducto($id) {
    global $productoModel;
    if ($productoModel->eliminar($id)) {
        echo json_encode(["mensaje" => "Producto eliminado", "id" => $id]);
    } else {
        echo json_encode(["error" => "No se pudo eliminar"]);
    }
}
?>
