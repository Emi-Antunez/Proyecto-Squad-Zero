<?php
session_start();
require "../backend/config/database.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: http://localhost/Proyecto-Squad-Zero/backend/controllers/login.php");
    exit;
}

$rol = $_SESSION["usuario_rol"] ?? "";

// Filtros
$busqueda = $_GET['busqueda'] ?? '';
$orden = $_GET['orden'] ?? '';

$sql = "SELECT * FROM productos WHERE 1";
if ($busqueda !== '') {
    $sql .= " AND nombre LIKE :busqueda";
}
if ($orden === 'precio') {
    $sql .= " ORDER BY precio ASC";
} elseif ($orden === 'fecha') {
    $sql .= " ORDER BY fecha_creacion DESC";
} else {
    $sql .= " ORDER BY fecha_creacion DESC";
}

$stmt = $pdo->prepare($sql);
if ($busqueda !== '') {
    $stmt->bindValue(':busqueda', "%$busqueda%");
}
// Búsqueda y ordenamiento
$buscar = $_GET['buscar'] ?? '';
$orden = $_GET['orden'] ?? 'fecha'; // Por defecto: fecha

$sql = "SELECT * FROM productos WHERE 1";
$params = [];

if (!empty($buscar)) {
    $sql .= " AND nombre LIKE :buscar";
    $params[':buscar'] = "%$buscar%";
}

if ($orden === 'precio') {
    $sql .= " ORDER BY precio ASC";
} else {
    $sql .= " ORDER BY fecha_creacion DESC";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos</title>
    <link rel="stylesheet" href="../styles/producto.css">
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
                <li><a href="carrito.php">Carrito 🛒</a></li>
                <li><a href="../public/logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </div>
</header>

<h1 class="titulo">Productos</h1>

<?php if (isset($_SESSION["mensaje_exito"])): ?>
    <div class="mensaje-exito">
        <?= $_SESSION["mensaje_exito"] ?>
        <?php unset($_SESSION["mensaje_exito"]); ?>
    </div>
<?php endif; ?>

<form method="GET" class="buscador-orden" style="text-align:center; margin-bottom: 30px;">
    <input 
        type="text" 
        name="buscar" 
        placeholder="Buscar producto..." 
        value="<?= htmlspecialchars($buscar) ?>"
        style="padding: 8px; width: 200px; border-radius: 6px; border: 1px solid #ccc;"
    >
    
    <select name="orden" style="padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
        <option value="fecha" <?= $orden === 'fecha' ? 'selected' : '' ?>>Más nuevos</option>
        <option value="precio" <?= $orden === 'precio' ? 'selected' : '' ?>>Precio: menor a mayor</option>
    </select>
    
    <button type="submit" style="padding: 8px 16px; background-color: #2c502e; color: white; border: none; border-radius: 6px; cursor: pointer;">
        Buscar
    </button>
</form>

<?php if (!empty($buscar)): ?>
    <p style="text-align: center; margin-bottom: 20px;">
        Se encontraron <?= count($productos) ?> resultados para "<strong><?= htmlspecialchars($buscar) ?></strong>"
    </p>
<?php endif; ?>

<?php if ($rol === 'admin'): ?>
    <a href="../public/agregar_producto.php" class="agregar-link">➕ Agregar Producto</a>
<?php endif; ?>

<div class="productos-container">
    <?php foreach ($productos as $producto): ?>
        <div class="producto-card" onclick="window.location.href='producto_detalle.php?id=<?= $producto["id"] ?>'">
            <img src="../images/<?= htmlspecialchars($producto["imagen"]) ?>" alt="<?= htmlspecialchars($producto["nombre"]) ?>">
            <h3><?= htmlspecialchars($producto["nombre"]) ?></h3>
            <p><?= htmlspecialchars($producto["descripcion"]) ?></p>
            <p class="precio">$<?= number_format($producto["precio"], 2) ?></p>

            <form method="POST" action="../public/agregar_al_carrito.php" class="form-carrito" onclick="event.stopPropagation();">
                <input type="hidden" name="producto_id" value="<?= $producto['id'] ?>">
                <label for="cantidad_<?= $producto['id'] ?>">Cantidad:</label>
                <input type="number" id="cantidad_<?= $producto['id'] ?>" name="cantidad" min="1" value="1" required>
                <button type="submit">Agregar al carrito</button>
            </form>

            <?php if (isset($_SESSION["usuario_rol"]) && $_SESSION["usuario_rol"] === "admin"): ?>
                <div class="admin-actions" onclick="event.stopPropagation();">
                    <a href="editar_producto.php?id=<?= $producto["id"] ?>" class="btn-editar">Editar</a>
                    <form method="POST" action="../public/eliminar_producto.php" onsubmit="return confirm('¿Seguro que querés eliminar este producto?');">
                        <input type="hidden" name="id" value="<?= $producto["id"] ?>">
                        <button type="submit" class="btn-eliminar">Eliminar</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<footer>
    <p>&copy; 2025 Mi Comercio. Todos los derechos reservados.</p>

    <footer>
    <div class="footer-container">
        <div class="footer-col">
            <h4>Enlaces</h4>
            <ul>
                <li><a href="#">Inicio</a></li>
                <li><a href="#">Productos</a></li>
                <li><a href="#">Sobre Nosotros</a></li>
                <li><a href="#">Contacto</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Contacto</h4>
            <p>📞 Tel: +54 9 11 2345-6789</p>
            <p>📧 Email: contacto@miempresa.com</p>
        </div>

        <div class="footer-col">
            <h4>Formas de pago</h4>
            <div class="metodos-pago">
                <span>💳 Visa</span>
                <span>💳 MasterCard</span>
                <span>💳 MercadoPago</span>
                <span>💰 Efectivo</span>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; 2025 Mi Empresa. Todos los derechos reservados.</p>
    </div>
</footer>

</footer>

<script>
    const form = document.querySelector('.buscador-orden');
    const productosContainer = document.querySelector('.productos-container');

    // Agregar fade-in a los productos al cargar
    window.onload = () => {
        const cards = productosContainer.querySelectorAll('.producto-card');
        setTimeout(() => {
            cards.forEach(card => {
                card.classList.add('visible');
            });
        }, 100);
    };

    // Agregar fade-out antes de hacer el filtro/orden
    form.addEventListener('submit', function (e) {
        const cards = productosContainer.querySelectorAll('.producto-card');
        cards.forEach(card => {
            card.classList.add('fade-out');
        });

        setTimeout(() => {
            form.submit();
        }, 500);
        e.preventDefault();
    });
</script>

</body>
</html>
