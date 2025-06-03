<?php
session_start();
require "backend/config/database.php";

// Verificar si es admin
if (!isset($_SESSION["usuario_id"]) || $_SESSION["usuario_rol"] !== "admin") {
    header("Location: producto.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $id = (int) $_POST["id"];
    
    // Borrar el producto
    $stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: producto.php");
exit;
