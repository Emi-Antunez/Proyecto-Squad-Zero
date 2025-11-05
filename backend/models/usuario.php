<?php
require_once __DIR__ . '/../database/conexion.php';

class Usuario {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function obtenerTodos() {
        $stmt = $this->conn->prepare("SELECT id, nombre, apellido, gmail, usuario, rol, foto_perfil FROM usuarios");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerPorId($id) {
        $stmt = $this->conn->prepare("SELECT id, nombre, apellido, gmail, usuario, foto_perfil FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function obtenerPorUsuario($usuario) {
    $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

    public function obtenerReservasPorUsuario($id_usuario) {
        $stmt = $this->conn->prepare("
            SELECT r.*, u.nombre, u.apellido, u.gmail
            FROM reservas r
            JOIN usuarios u ON r.id_usuario = u.id
            WHERE r.id_usuario = ?
            ORDER BY r.fecha DESC, r.hora DESC
        ");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

public function existeUsuarioOGmail($usuario, $gmail) {
    $stmt = $this->conn->prepare("SELECT id FROM usuarios WHERE usuario=? OR gmail=?");
    $stmt->bind_param("ss", $usuario, $gmail);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

public function agregar($nombre, $apellido, $gmail, $usuario, $contrasena, $foto_perfil = null) {
    if ($this->existeUsuarioOGmail($usuario, $gmail)) {
        return false;
    }
    $hash = password_hash($contrasena, PASSWORD_DEFAULT);
    $stmt = $this->conn->prepare("INSERT INTO usuarios (nombre, apellido, gmail, usuario, contrasena, foto_perfil) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nombre, $apellido, $gmail, $usuario, $hash, $foto_perfil);
    if (!$stmt->execute()) {
        // Para depuración, imprime el error de MySQL
        error_log("Error MySQL: " . $stmt->error);
        return false;
    }
    return true;
}

function agregarUsuario($nombre, $apellido, $gmail, $usuario, $contrasena) {
    global $usuarioModel;
    if ($usuarioModel->agregar($nombre, $apellido, $gmail, $usuario, $contrasena)) {
        echo json_encode(["mensaje" => "Usuario agregado"]);
    } else {
        echo json_encode(["error" => "No se pudo agregar. El usuario o el correo ya existen, o hubo un error en la base de datos."]);
    }
}

    public function modificar($id, $nombre, $apellido, $gmail, $usuario, $contrasena) {
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE usuarios SET nombre=?, apellido=?, gmail=?, usuario=?, contrasena=? WHERE id=?");
        $stmt->bind_param("sssssi", $nombre, $apellido, $gmail, $usuario, $hash, $id);
        return $stmt->execute();
    }

    public function eliminar($id) {
        $stmt = $this->conn->prepare("DELETE FROM usuarios WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>