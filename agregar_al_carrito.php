<?php
session_start();

// Verificamos que llegaron los datos
if (!isset($_POST['producto_id'], $_POST['cantidad'])) {
    // Redirigir si faltan datos
    header("Location: producto.php");
    exit;
}

// Sanitizar y validar datos
$producto_id = intval($_POST['producto_id']);
$cantidad = max(1, intval($_POST['cantidad'])); // Siempre al menos 1

// Obtener los datos del producto desde la base de datos
require 'backend/config/database.php'; // Asegúrate de incluir la conexión

$stmt = $pdo->prepare("SELECT id, nombre, precio FROM productos WHERE id = :id");
$stmt->execute(['id' => $producto_id]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificamos si el producto existe
if ($producto) {
    // Inicializamos el carrito si no existe
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    // Si ya hay ese producto en el carrito, sumamos cantidad
    if (isset($_SESSION['carrito'][$producto_id])) {
        $_SESSION['carrito'][$producto_id]['cantidad'] += $cantidad;
    } else {
        $_SESSION['carrito'][$producto_id] = [
            'nombre' => $producto['nombre'],
            'precio' => $producto['precio'],
            'cantidad' => $cantidad,
        ];
    }
}

header("Location: producto.php");
exit;
