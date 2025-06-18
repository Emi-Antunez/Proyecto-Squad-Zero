<?php
require_once __DIR__ . '/../database/conexion.php';

class Reserva {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }


    public function obtenerTodas() {
    $stmt = $this->conn->prepare("SELECT r.*, u.nombre, u.apellido FROM reservas r JOIN usuarios u ON r.id_usuario = u.id");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

    public function obtenerPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM reservas WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

// ...existing code...
public function agregar($id_usuario, $tour, $fecha, $hora, $cantidad_personas) {
    $stmt = $this->conn->prepare("INSERT INTO reservas (id_usuario, tour, fecha, hora, cantidad_personas) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssi", $id_usuario, $tour, $fecha, $hora, $cantidad_personas);
    return $stmt->execute();
}

    public function modificar($id, $id_usuario, $tour, $fecha, $hora, $cantidad_personas) {
    $stmt = $this->conn->prepare("UPDATE reservas SET id_usuario=?, tour=?, fecha=?, hora=?, cantidad_personas=? WHERE id=?");
    $stmt->bind_param("isssii", $id_usuario, $tour, $fecha, $hora, $cantidad_personas, $id);
    return $stmt->execute();
}
// ...existing code...

    public function eliminar($id) {
        $stmt = $this->conn->prepare("DELETE FROM reservas WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>