<?php
session_start();
require "backend/config/database.php";

$carrito = $_SESSION["carrito"] ?? [];

$productos = [];

if (!empty($carrito)) {
    $ids = implode(",", array_keys($carrito));
    $stmt = $pdo->query("SELECT * FROM productos WHERE id IN ($ids)");
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="styles/carrito.css">
</head>
<body>

<h1>Carrito de Compras</h1>

<?php if (empty($carrito)): ?>
    <p>No hay productos en el carrito.</p>
<?php else: ?>
    <ul>
        <?php foreach ($productos as $producto): ?>
            <li>
                <?= htmlspecialchars($producto["nombre"]) ?> - 
                Cantidad: <?= $carrito[$producto["id"]] ?> - 
                Precio: $<?= number_format($producto["precio"], 2) ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<div class="carrito-container">
    <!-- Aquí irán los productos con clase .carrito-item -->
    
    <p class="total">Total: $123.45</p>

    <div class="carrito-acciones">
        <a href="producto.php">⬅ Seguir comprando</a>
        <button onclick="alert('Compra finalizada')">Finalizar compra 🛒</button>
    </div>
</div>

<div id="notificacion-carrito" class="notificacion-oculta">
    ✅ Producto agregado al carrito
</div>

</body>
</html>
