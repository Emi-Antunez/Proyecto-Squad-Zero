<?php
require "backend/config/database.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"]);
    $apellido = trim($_POST["apellido"]);
    $gmail = trim($_POST["email"]);
    $usuario = trim($_POST["usuario"]);
    $contrasena = $_POST["contrasena"];
    $confirmar = $_POST["confirmar"];

    if ($contrasena !== $confirmar) {
        $mensaje = "⚠️ Las contraseñas no coinciden.";
    } else {
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellido, gmail, usuario, contrasena) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nombre, $apellido, $gmail, $usuario, $hash]);
            $mensaje = "✅ Usuario registrado con éxito.";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Error por clave duplicada
                if (str_contains($e->getMessage(), 'gmail')) {
                    $mensaje = "❌ El correo electrónico ya está registrado.";
                } elseif (str_contains($e->getMessage(), 'usuario')) {
                    $mensaje = "❌ El nombre de usuario ya está en uso.";
                } else {
                    $mensaje = "❌ Ya existe un usuario con los datos ingresados.";
                }
            } else {
                $mensaje = "❌ Error inesperado al registrar: " . $e->getMessage();
            }
        }
    }
}
?>


<!-- Tu formulario HTML -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="Styles/RegisterStyle.css">
</head>
<body>
    <form method="post" action="register.php">
        <h2>Registrarse</h2>
        <?php if ($mensaje): ?>
            <p><?= $mensaje ?></p>
        <?php endif; ?>

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" required>

        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" required>

        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" required>

        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" required>

        <label for="confirmar">Confirmar Contraseña:</label>
        <input type="password" id="confirmar" name="confirmar" required>

        <input type="submit" value="Registrarse">
        <a href="index.html">¿Ya tienes una cuenta? Inicia sesión</a>
    </form>
</body>
</html>
