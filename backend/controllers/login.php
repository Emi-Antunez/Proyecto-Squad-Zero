<?php
session_start();

require "../config/database.php";

// Bloqueo de caché
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Si ya está logueado, redirigir
if (isset($_SESSION["usuario_id"])) {
    header("Location: ../../principal.php");

    exit;
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST["username"];
    $contrasena = $_POST["password"];

    // Buscar usuario
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $usuarioData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Mostrar información temporalmente en la página
    echo "<pre>";
    var_dump($usuarioData);
    var_dump($_SESSION["usuario_rol"]);
    echo "</pre>";

    if ($usuarioData && password_verify($contrasena, $usuarioData["contrasena"])) {
        // Guardamos en sesión
        $_SESSION["usuario_id"] = $usuarioData["id"];
        $_SESSION["usuario_nombre"] = $usuarioData["usuario"];
        $_SESSION["usuario_rol"] = $usuarioData["rol"]; // Guardamos el rol
        // Redirigimos
        header("Location: ../../principal.php");
        exit;
    } else {
        $mensaje = "❌ Usuario o contraseña incorrectos.";
    }
}
?>

<!-- Formulario HTML -->
<?php if ($mensaje): ?>
    <p style="color:red;"><?= $mensaje ?></p>
<?php endif; ?>

<!-- Formulario de login -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../../Styles/IndexStyle.css">
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
        <a href="../../register.php">¿No tienes una cuenta? Regístrate</a>
    </form>

</body>
</html>
