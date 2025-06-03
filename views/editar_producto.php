<?php
require "../backend/config/database.php";
session_start();

// Solo admins pueden editar
if (!isset($_SESSION["usuario_id"]) || $_SESSION["usuario_rol"] !== "admin") {
    header("Location: producto.php");
    exit;
}

// Verificar ID
if (!isset($_GET["id"])) {
    echo "ID de producto no especificado.";
    exit;
}

$id = $_GET["id"];

// Obtener datos del producto
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$id]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    echo "Producto no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="../styles/editar_producto.css">

</head>
<body>

    <h1>Editar Producto</h1>

    <form action="actualizar_producto.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $producto['id'] ?>">

        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required><br>

        <label>Descripción:</label>
        <textarea name="descripcion" required><?= htmlspecialchars($producto['descripcion']) ?></textarea><br>

        <label>Precio:</label>
        <input type="number" step="0.01" name="precio" value="<?= $producto['precio'] ?>" required><br>

        <label>Imagen actual:</label><br>
        <img src="images/<?= htmlspecialchars($producto['imagen']) ?>" width="150"><br>

        <label>Cambiar imagen:</label>
        <input type="file" name="imagen"><br><br>

        <button type="submit">Guardar Cambios</button>
    </form>

</body>
</html>
