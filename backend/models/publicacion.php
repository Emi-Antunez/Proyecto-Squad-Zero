<?php
require_once __DIR__ . '/../database/conexion.php';

class Publicacion {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function obtenerTodas() {
        $stmt = $this->conn->prepare("SELECT * FROM publicaciones ORDER BY fecha DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM publicaciones WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function agregar($titulo, $descripcion, $imagen, $fecha) {
        $stmt = $this->conn->prepare("INSERT INTO publicaciones (titulo, descripcion, imagen, fecha) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $titulo, $descripcion, $imagen, $fecha);
        if (!$stmt->execute()) {
            error_log("Error MySQL: " . $stmt->error);
            return false;
        }
        return $this->conn->insert_id;
    }

    public function modificar($id, $titulo, $descripcion, $imagen, $fecha) {
        if ($imagen) {
            $stmt = $this->conn->prepare("UPDATE publicaciones SET titulo=?, descripcion=?, imagen=?, fecha=? WHERE id=?");
            $stmt->bind_param("ssssi", $titulo, $descripcion, $imagen, $fecha, $id);
        } else {
            $stmt = $this->conn->prepare("UPDATE publicaciones SET titulo=?, descripcion=?, fecha=? WHERE id=?");
            $stmt->bind_param("sssi", $titulo, $descripcion, $fecha, $id);
        }
        return $stmt->execute();
    }

    public function eliminar($id) {
        // Primero obtener la imagen para eliminarla del servidor
        $publicacion = $this->obtenerPorId($id);
        if ($publicacion && $publicacion['imagen']) {
            $rutaImagen = __DIR__ . '/../../' . $publicacion['imagen'];
            if (file_exists($rutaImagen)) {
                unlink($rutaImagen);
            }
        }
        
        $stmt = $this->conn->prepare("DELETE FROM publicaciones WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>