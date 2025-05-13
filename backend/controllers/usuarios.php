<?php
require "../models/Usuario.php";
$usuarioModel = new Usuario($pdo);

function obtenerUsuarios() {
    global $usuarioModel;
    echo json_encode($usuarioModel->obtenerTodos());
}

function agregarUsuario($nombre, $apellido, $gmail, $usuario, $contrasena) {
    global $usuarioModel;
    if ($usuarioModel->agregar($nombre, $apellido, $gmail, $usuario, $contrasena)) {
        echo json_encode(["message" => "Usuario agregado"]);
    } else {
        echo json_encode(["error" => "Error al agregar el usuario"]);
    }
}

function eliminarUsuarios() {
    global $usuarioModel;
    if (!isset($_GET['id'])) {
        echo json_encode(["success" => false, "error" => "Falta ID"]);
        return;
    }
    $id = $_GET['id'];
    echo json_encode(["success" => $usuarioModel->eliminar($id)]);
}

