<?php
session_start();
require "backend/config/database.php";

// Proteger acceso
if (!isset($_SESSION["usuario_id"])) {
    header("Location: http://localhost/Proyecto-Squad-Zero/backend/controllers/login.php");
    exit;
}

// Obtener datos de sesión
$rol = $_SESSION["usuario_rol"] ?? "";

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

    <header>
        <div class="container">
            <h1>Mi Comercio</h1>
            <nav>
                <ul>
                    <li><a href="principal.php">Inicio</a></li>
                    <li><a href="producto.php">Productos</a></li>
                    <li><a href="#">Servicios</a></li>
                    <li><a href="#">Contacto</a></li>
                    <li><a href="logout.php">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </div>
    </header>

<h1 class="titulo">Productos</h1>

<?php if ($rol === 'admin'): ?>
    <a href="agregar_producto.php" class="agregar-link">➕ Agregar Producto</a>
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
