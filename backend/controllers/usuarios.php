<?php
require_once __DIR__ . '/../models/usuario.php';

$usuarioModel = new Usuario($conn); // Instancia del modelo

function listarUsuarios() {
    global $usuarioModel;
    echo json_encode($usuarioModel->obtenerTodos());
}

function mostrarUsuario($id) {
    global $usuarioModel;
    $usuario = $usuarioModel->obtenerPorId($id);
    if ($usuario) {
        echo json_encode($usuario);
    } else {
        echo json_encode(["error" => "Usuario no encontrado"]);
    }
}

function loginUsuario($usuario, $contrasena) {
    global $conn;
    $stmt = $conn->prepare("SELECT id, usuario, contrasena, rol FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if ($user && password_verify($contrasena, $user['contrasena'])) {
        $_SESSION['id_usuario'] = $user['id'];
        $_SESSION['rol'] = $user['rol'];
        echo json_encode([
            "mensaje" => "Login exitoso",
            "id_usuario" => $user['id'],
            "usuario" => $user['usuario'],
            "rol" => $user['rol']
        ]);
    } else {
        echo json_encode(["error" => "Usuario o contraseña incorrectos"]);
    }
}

function agregarUsuario($nombre, $apellido, $gmail, $usuario, $contrasena) {
    global $usuarioModel;
    if ($usuarioModel->agregar($nombre, $apellido, $gmail, $usuario, $contrasena)) {
        echo json_encode(["mensaje" => "Usuario agregado"]);
    } else {
        echo json_encode(["error" => "No se pudo agregar"]);
    }
}

function modificarUsuario($id, $nombre, $apellido, $gmail, $usuario, $contrasena) {
    global $usuarioModel;
    if ($usuarioModel->modificar($id, $nombre, $apellido, $gmail, $usuario, $contrasena)) {
        echo json_encode(["mensaje" => "Usuario modificado", "id" => $id]);
    } else {
        echo json_encode(["error" => "No se pudo modificar"]);
    }
}

function eliminarUsuario($id) {
    global $usuarioModel;
    if ($usuarioModel->eliminar($id)) {
        echo json_encode(["mensaje" => "Usuario eliminado", "id" => $id]);
    } else {
        echo json_encode(["error" => "No se pudo eliminar"]);
    }
}

function cambiarRolUsuario($id, $nuevoRol) {
    global $conn;
    
    // Validar que el rol sea válido
    if ($nuevoRol !== 'admin' && $nuevoRol !== 'usuario') {
        echo json_encode(["error" => "Rol inválido. Debe ser 'admin' o 'usuario'"]);
        return;
    }
    
    $stmt = $conn->prepare("UPDATE usuarios SET rol = ? WHERE id = ?");
    $stmt->bind_param("si", $nuevoRol, $id);
    
    if ($stmt->execute()) {
        echo json_encode(["mensaje" => "Rol actualizado exitosamente", "id" => $id, "rol" => $nuevoRol]);
    } else {
        echo json_encode(["error" => "No se pudo actualizar el rol"]);
    }
    
    $stmt->close();
}
?>