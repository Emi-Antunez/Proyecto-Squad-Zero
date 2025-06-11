<?php
// Se importa el archivo que contiene la configuración de la base de datos, que establece la conexión
require_once __DIR__ . '/../database/conexion.php'; // Importar la conexión a la base de datos

// Definición de la clase Producto que interactuará con la tabla 'productos' en la base de datos
class Producto {
    private $conn; // Propiedad privada para almacenar la conexión mysqli

    // El constructor recibe el objeto $conn (conexión a la base de datos) y lo asigna a la propiedad $this->conn
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Método para obtener todos los productos de la base de datos
    public function obtenerTodos() {
        $stmt = $this->conn->prepare("SELECT * FROM productos");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Método para obtener un producto por ID
    public function obtenerPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

// ...existing code...
public function agregar($nombre, $descripcion, $precio, $stock, $imagen) {
    $stmt = $this->conn->prepare("INSERT INTO productos (nombre, descripcion, precio, stock, imagen) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdss", $nombre, $descripcion, $precio, $stock, $imagen);
    return $stmt->execute();
}

public function modificar($id, $nombre, $descripcion, $precio, $stock, $imagen) {
    $stmt = $this->conn->prepare("UPDATE productos SET nombre=?, descripcion=?, precio=?, stock=?, imagen=? WHERE id=?");
    $stmt->bind_param("ssdssi", $nombre, $descripcion, $precio, $stock, $imagen, $id);
    return $stmt->execute();
}
// ...existing code...

    // Método para eliminar un producto
    public function eliminar($id) {
        $stmt = $this->conn->prepare("DELETE FROM productos WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
