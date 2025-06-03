<?php
session_start();
require "../backend/config/database.php";

// Verificar si es admin
if (!isset($_SESSION["usuario_id"]) || $_SESSION["usuario_rol"] !== "admin") {
header("Location: /Proyecto-Squad-Zero/views/producto.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $id = (int) $_POST["id"];

    try {
        $check = $pdo->prepare("SELECT id FROM productos WHERE id = ?");
        $check->execute([$id]);

        if ($check->rowCount() > 0) {
            $stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
            $stmt->execute([$id]);
        }

    } catch (PDOException $e) {
        die("Error al eliminar producto: " . $e->getMessage());
    }
}

header("Location: /Proyecto-Squad-Zero/views/producto.php");
exit;
