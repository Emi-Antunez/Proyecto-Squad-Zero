<?php
require_once __DIR__ . '/../models/publicacion.php';

$publicacionModel = new Publicacion($conn);

function listarPublicaciones() {
    global $publicacionModel;
    echo json_encode($publicacionModel->obtenerTodas());
}

function mostrarPublicacion($id) {
    global $publicacionModel;
    $publicacion = $publicacionModel->obtenerPorId($id);
    if ($publicacion) {
        echo json_encode($publicacion);
    } else {
        echo json_encode(["error" => "Publicación no encontrada"]);
    }
}

function agregarPublicacion($titulo, $descripcion, $imagen, $fecha) {
    global $publicacionModel;
    $id = $publicacionModel->agregar($titulo, $descripcion, $imagen, $fecha);
    if ($id) {
        echo json_encode(["mensaje" => "Publicación agregada", "id" => $id]);
    } else {
        echo json_encode(["error" => "No se pudo agregar la publicación"]);
    }
}

function modificarPublicacion($id, $titulo, $descripcion, $imagen, $fecha) {
    global $publicacionModel;
    if ($publicacionModel->modificar($id, $titulo, $descripcion, $imagen, $fecha)) {
        echo json_encode(["mensaje" => "Publicación modificada", "id" => $id]);
    } else {
        echo json_encode(["error" => "No se pudo modificar la publicación"]);
    }
}

function eliminarPublicacion($id) {
    global $publicacionModel;
    if ($publicacionModel->eliminar($id)) {
        echo json_encode(["mensaje" => "Publicación eliminada", "id" => $id]);
    } else {
        echo json_encode(["error" => "No se pudo eliminar la publicación"]);
    }
}

function subirImagenPublicacion() {
    if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(["error" => "No se recibió ninguna imagen o hubo un error en la carga"]);
        return;
    }

    $archivo = $_FILES['imagen'];
    $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (!in_array($extension, $extensionesPermitidas)) {
        echo json_encode(["error" => "Formato de imagen no permitido"]);
        return;
    }

    // Crear directorio si no existe
    $directorioDestino = __DIR__ . '/../../img/publicaciones/';
    if (!file_exists($directorioDestino)) {
        mkdir($directorioDestino, 0777, true);
    }

    // Generar nombre único
    $nombreArchivo = 'pub_' . time() . '_' . uniqid() . '.' . $extension;
    $rutaDestino = $directorioDestino . $nombreArchivo;

    if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
        echo json_encode([
            "mensaje" => "Imagen subida correctamente",
            "ruta" => 'img/publicaciones/' . $nombreArchivo
        ]);
    } else {
        echo json_encode(["error" => "Error al mover el archivo"]);
    }
}
?>