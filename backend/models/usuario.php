<?php
require "../config/database.php"; // Conexión a la base de datos

// Función para registrar un nuevo usuario
function registrarUsuario($nombre, $apellido, $gmail, $usuario, $contrasena, $confirmar) {
    // Validar que las contraseñas coincidan
    if ($contrasena !== $confirmar) {
        return "Las contraseñas no coinciden.";
    }

    // Verificar si el correo ya está registrado
    global $pdo;
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE gmail = ?");
    $stmt->execute([$gmail]);
    if ($stmt->rowCount() > 0) {
        return "Este correo ya está registrado.";
    }

    // Verificar si el usuario ya está registrado
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    if ($stmt->rowCount() > 0) {
        return "Este usuario ya está registrado.";
    }

    // Encriptar la contraseña
    $hash = password_hash($contrasena, PASSWORD_BCRYPT);

    // Insertar el nuevo usuario en la base de datos
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellido, gmail, usuario, contrasena) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$nombre, $apellido, $gmail, $usuario, $hash])) {
        return "Usuario registrado con éxito.";
    } else {
        return "Error al registrar el usuario.";
    }
}
