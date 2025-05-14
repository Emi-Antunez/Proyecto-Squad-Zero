<?php
session_start();
require "backend/config/database.php";

// Verificar si el usuario está logueado y es admin
if (!isset($_SESSION["usuario_id"]) || $_SESSION["usuario_rol"] !== "admin") {
    header("Location: producto.php");
    exit;
}

// Verificar que se recibieron datos del formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"];
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $precio = $_POST["precio"];

    // Obtener datos actuales del producto
    $stmt = $pdo->prepare("SELECT imagen FROM productos WHERE id = ?");
    $stmt->execute([$id]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        echo "Producto no encontrado.";
        exit;
    }

    $imagen = $producto["imagen"];

    // Verificar si se subió una nueva imagen
    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === UPLOAD_ERR_OK) {
        $nombreArchivo = basename($_FILES["imagen"]["name"]);
        $rutaDestino = "images/" . $nombreArchivo;

        // Mover imagen al directorio
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino)) {
            $imagen = $nombreArchivo;
        } else {
            echo "Error al subir la imagen.";
            exit;
        }
    }

    // Actualizar producto en base de datos
    $stmt = $pdo->prepare("UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, imagen = ? WHERE id = ?");
    $stmt->execute([$nombre, $descripcion, $precio, $imagen, $id]);

$_SESSION['mensaje_exito'] = "Producto actualizado correctamente.";
header("Location: producto.php");
exit;
} else {
    echo "Acceso no válido.";
}


