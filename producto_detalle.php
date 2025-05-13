<?php
session_start();
require "backend/config/database.php";

// Proteger con sesión
if (!isset($_SESSION["usuario_id"])) {
    header("Location: http://localhost/Proyecto-Squad-Zero/backend/controllers/login.php");
    exit;
}

// Obtener productos
$stmt = $pdo->query("SELECT * FROM productos ORDER BY fecha_creacion DESC");
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos</title>
    <link rel="stylesheet" href="styles/producto.css">
</head>
<body>

<h1>Productos</h1>

<?php if (isset($_SESSION["usuario_rol"]) && $_SESSION["usuario_rol"] === "admin"): ?>
    <div class="add-product-btn">
        <a href="agregar_producto.php">Agregar Producto</a>
    </div>
<?php endif; ?>

<div class="productos-container">
    <?php foreach ($productos as $producto): ?>
        <div class="producto-card" onclick="window.location.href='producto_detalle.php?id=<?= $producto["id"] ?>'">
            <img src="images/<?= htmlspecialchars($producto["imagen"]) ?>" alt="<?= htmlspecialchars($producto["nombre"]) ?>">
            <h3><?= htmlspecialchars($producto["nombre"]) ?></h3>
            <p><?= htmlspecialchars($producto["descripcion"]) ?></p>
            <p class="precio">$<?= number_format($producto["precio"], 2) ?></p>
        </div>
    <?php endforeach; ?>
</div>

<footer>
    <p>&copy; 2025 Mi Comercio. Todos los derechos reservados.</p>
</footer>

</body>
</html>
