<?php
session_start();
require "backend/config/database.php";

// Obtener el carrito desde la sesión
$carrito = $_SESSION["carrito"] ?? [];

// Obtener los productos de la base de datos según los productos en el carrito
$productos = [];
if (!empty($carrito)) {
    $ids = implode(",", array_keys($carrito)); // Obtener los IDs de los productos
    $stmt = $pdo->query("SELECT * FROM productos WHERE id IN ($ids)");
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Calcular el total del carrito
$total = 0;
foreach ($productos as $producto) {
    $total += $producto['precio'] * $carrito[$producto['id']]['cantidad']; // Calcular precio total por producto
}

// Eliminar productos del carrito
if (isset($_GET['eliminar'])) {
    $producto_id = $_GET['eliminar'];
    unset($_SESSION['carrito'][$producto_id]); // Eliminar producto del carrito
    header("Location: carrito.php");
    exit;
}

// Actualizar las cantidades de productos en el carrito
if (isset($_POST['actualizar'])) {
    foreach ($_POST['cantidad'] as $producto_id => $cantidad) {
        if ($cantidad > 0) {
            $_SESSION['carrito'][$producto_id]['cantidad'] = $cantidad; // Actualizar cantidad
        } else {
            unset($_SESSION['carrito'][$producto_id]); // Eliminar producto si la cantidad es 0
        }
    }
    header("Location: carrito.php");
    exit;
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
<?php endif; ?>

<?php if (!empty($carrito)): ?>
    <form action="carrito.php" method="POST" id="form-carrito">
        <ul class="carrito-lista">
            <?php foreach ($productos as $producto): ?>
                <li class="carrito-item">
                    <img src="images/<?= htmlspecialchars($producto["imagen"]) ?>" alt="<?= htmlspecialchars($producto["nombre"]) ?>" class="carrito-imagen">
                    <div class="carrito-info">
                        <h3><?= htmlspecialchars($producto["nombre"]) ?></h3>
                        <p>Precio: $<?= number_format($producto["precio"], 2) ?></p>
                        <p>Cantidad: 
                            <input type="number" name="cantidad[<?= $producto['id'] ?>]" value="<?= $carrito[$producto['id']]['cantidad'] ?>" min="1" data-product-id="<?= $producto['id'] ?>" class="cantidad-input" required>
                        </p>
                    </div>
                    <a href="carrito.php?eliminar=<?= $producto['id'] ?>" class="btn-eliminar">Eliminar</a>
                </li>
            <?php endforeach; ?>
        </ul>
        
        <div class="carrito-total">
            <p><strong>Total: $<?= number_format($total, 2) ?></strong></p>
        </div>
    </form>
<?php endif; ?>

<div class="carrito-acciones">
    <a href="producto.php">⬅ Seguir comprando</a>
    <button onclick="alert('Compra finalizada')">Finalizar compra 🛒</button>
</div>

<script>
// Hacer que la cantidad se actualice automáticamente cuando el usuario cambie el valor
document.querySelectorAll('.cantidad-input').forEach(input => {
    input.addEventListener('change', function() {
        document.getElementById('form-carrito').submit();
    });
});
</script>

</body>
</html>
