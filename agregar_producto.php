<?php
session_start();
require "backend/config/database.php";

// Verificar si el usuario está logueado y es admin
if (!isset($_SESSION["usuario_id"]) || $_SESSION["usuario_rol"] !== 'admin') {
    header("Location: index.php");
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
        $targetDir = "images/";
        $targetFile = $targetDir . basename($imagen);
        move_uploaded_file($_FILES["imagen"]["tmp_name"], $targetFile);
    }

    // Insertar producto en la base de datos
    $stmt = $pdo->prepare("INSERT INTO productos (nombre, descripcion, precio, stock, imagen) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nombre, $descripcion, $precio, $stock, $imagen]);

    // Mensaje de éxito
    $mensaje = "Producto agregado exitosamente!";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f4f4f4; }
        h1 { text-align: center; }
        form { max-width: 600px; margin: auto; background-color: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        input, textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        button { padding: 10px 20px; background-color: #28a745; color: white; border: none; cursor: pointer; border-radius: 5px; }
        button:hover { background-color: #218838; }
        .mensaje { text-align: center; margin-bottom: 20px; color: green; font-weight: bold; }
    </style>
</head>
<body>

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

</body>
</html>
