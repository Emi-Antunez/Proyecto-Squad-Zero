<?php
session_start();
$id = $_POST['producto_id'] ?? null;

if (!$id) {
    header("Location: producto.php");
    exit;
}

if (!isset($_SESSION["carrito"])) {
    $_SESSION["carrito"] = [];
}

if (!isset($_SESSION["carrito"][$id])) {
    $_SESSION["carrito"][$id] = 1;
} else {
    $_SESSION["carrito"][$id]++;
}

header("Location: producto.php");
exit;
