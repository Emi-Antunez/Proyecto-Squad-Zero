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
    
    $foto_perfil = null;
    
    // Manejar subida de foto de perfil
    if (isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['fotoPerfil'];
        
        // Validar tipo de archivo
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mime_type, $allowed_types)) {
            echo json_encode(["error" => "Tipo de archivo no permitido. Use JPG, PNG, GIF o WEBP"]);
            return;
        }
        
        // Validar tamaño (2MB)
        if ($file['size'] > 2 * 1024 * 1024) {
            echo json_encode(["error" => "La imagen es demasiado grande. Tamaño máximo: 2MB"]);
            return;
        }
        
        // Generar nombre único
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . uniqid() . '.' . $extension;
        $upload_dir = __DIR__ . '/../../img/perfiles/';
        
        // Crear directorio si no existe
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $upload_path = $upload_dir . $filename;
        
        // Mover archivo
        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            $foto_perfil = 'img/perfiles/' . $filename;
        } else {
            echo json_encode(["error" => "Error al subir la imagen"]);
            return;
        }
    }
    
    if ($usuarioModel->agregar($nombre, $apellido, $gmail, $usuario, $contrasena, $foto_perfil)) {
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