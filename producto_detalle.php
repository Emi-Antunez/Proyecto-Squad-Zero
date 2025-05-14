<?php
session_start();
require "backend/config/database.php";

// Verificar si hay ID en la URL
if (!isset($_GET["id"])) {
    header("Location: producto.php");
    exit;
}

$id = $_GET["id"];

// Obtener el producto desde la base de datos
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$id]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

// Si no se encontró el producto
if (!$producto) {
    echo "<h2>Producto no encontrado</h2>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($producto["nombre"]) ?> - Detalle</title>
    <link rel="stylesheet" href="styles/producto_detalle.css"> <!-- Podés usar uno nuevo o el mismo -->
</head>
<body>

    <header>
        <div class="container">
            <h1>Mi Comercio</h1>
            <nav>
                <ul>
        <li><a href="principal.php">Inicio</a></li>
        <li><a href="producto.php">Productos</a></li>
        <li><a href="servicios.php">Servicios</a></li>
        <li><a href="contacto.php">Contacto</a></li> 
        <li><a href="logout.php">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </div>
    </header>

<main>
    <div class="detalle-container">
        <img src="images/<?= htmlspecialchars($producto["imagen"]) ?>" alt="<?= htmlspecialchars($producto["nombre"]) ?>">
        <div class="detalle-info">
            <h2><?= htmlspecialchars($producto["nombre"]) ?></h2>
            <p><?= htmlspecialchars($producto["descripcion"]) ?></p>
            <p><strong>Precio:</strong> $<?= number_format($producto["precio"], 2) ?></p>
            <p><strong>Stock:</strong> <?= $producto["stock"] ?></p>
        </div>
    </div>
</main>

<footer>
    <p>&copy; 2025 Mi Comercio. Todos los derechos reservados.</p>
</footer>

</body>
</html>
