<?php
session_start();
require "backend/config/database.php";

$mensaje = "";

// Verificamos si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST["username"];
    $contrasena = $_POST["password"];

    // Buscamos al usuario en la base de datos
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $usuarioData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuarioData && password_verify($contrasena, $usuarioData["contrasena"])) {
        // Inicio de sesión exitoso
        $_SESSION["usuario_id"] = $usuarioData["id"];
        $_SESSION["usuario_nombre"] = $usuarioData["nombre"];
        header("Location: principal.php");
        exit;
    } else {
        $mensaje = "❌ Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="./Styles/IndexStyle.css">
</head>
<body>

    <form method="post" action="">
        <h2>Iniciar Sesión</h2>

        <?php if ($mensaje): ?>
            <p style="color: red;"><?= $mensaje ?></p>
        <?php endif; ?>

        <label for="username">Usuario:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>

        <input type="submit" value="Iniciar Sesión">
        <a href="register.php">¿No tienes una cuenta? Regístrate</a>
    </form>

</body>
</html>
