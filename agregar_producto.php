<?php
session_start();
require "backend/config/database.php";

// Verificar si el usuario está logueado y es admin
if (!isset($_SESSION["usuario_id"]) || $_SESSION["usuario_rol"] !== 'admin') {
    header("Location: http://localhost/Proyecto-Squad-Zero/backend/controllers/login.php");
    exit;
}

// Variables para mensajes de error o éxito
$mensaje = "";

// Procesar formulario para agregar producto
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $precio = $_POST["precio"];
    $stock = $_POST["stock"];
    $imagen = $_FILES["imagen"]["name"];

    // Subir la imagen
    if ($imagen) {
        $targetDir = __DIR__ . "/images/"; // Ruta absoluta
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true); // Crear carpeta si no existe
        }

        // Generar nombre único
        $extension = pathinfo($imagen, PATHINFO_EXTENSION);
        $nombreUnico = md5(uniqid(rand(), true)) . "." . $extension;

        $targetFile = $targetDir . $nombreUnico;

        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $targetFile)) {
            // Guardar el nombre generado en la base
            $stmt = $pdo->prepare("INSERT INTO productos (nombre, descripcion, precio, stock, imagen) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nombre, $descripcion, $precio, $stock, $nombreUnico]);
            $mensaje = "✅ Producto agregado exitosamente.";
        } else {
            $mensaje = "❌ Error al subir la imagen.";
        }
    } else {
        $mensaje = "❌ Debes seleccionar una imagen.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="styles/agregar_producto.css">
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
<h1>Agregar Producto</h1>

<?php if ($mensaje): ?>
    <div class="mensaje"><?= $mensaje ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <label for="nombre">Nombre del Producto:</label>
    <input type="text" id="nombre" name="nombre" required>

    <label for="descripcion">Descripción:</label>
    <textarea id="descripcion" name="descripcion"></textarea>

    <label for="precio">Precio:</label>
    <input type="number" id="precio" name="precio" step="0.01" required>

    <label for="stock">Stock:</label>
    <input type="number" id="stock" name="stock" required>

    <label for="imagen">Imagen:</label>
    <input type="file" id="imagen" name="imagen" accept="image/*" required>

    <button type="submit">Agregar Producto</button>
</form>
</main>

    <footer>
        <p>&copy; 2025 Mi Comercio. Todos los derechos reservados.</p>
    </footer>
    
</body>
</html>
