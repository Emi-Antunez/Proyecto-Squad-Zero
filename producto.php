<?php
session_start();
require "backend/config/database.php";

// Proteger acceso
if (!isset($_SESSION["usuario_id"])) {
    header("Location: index.php");
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
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f4f4f4; }
        h1 { text-align: center; }

        .productos-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            justify-content: center;
        }

        .producto-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            cursor: pointer;
            text-align: center;
        }

        .producto-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .producto-card h3 {
            margin: 10px;
            font-size: 18px;
        }

        .producto-card p {
            margin: 0 10px;
            font-size: 16px;
            color: #777;
        }

        .producto-card .precio {
            font-size: 20px;
            font-weight: bold;
            color: #28a745;
            margin: 10px 0;
        }

        .agregar-link {
            display: block;
            width: fit-content;
            margin: 20px auto;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
        }

        footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            background-color: #333;
            color: white;
        }
    </style>
</head>
<body>

<h1>Productos</h1>

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
