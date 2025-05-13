<?php
session_start();

// Bloqueo de caché
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Si ya está logueado, redirigir
if (isset($_SESSION["usuario_id"])) {
    header("Location: principal.php");
    exit;
}

require "backend/config/database.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST["username"];
    $contrasena = $_POST["password"];

    // Buscar usuario
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $usuarioData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuarioData && password_verify($contrasena, $usuarioData["contrasena"])) {
        $_SESSION["usuario_id"] = $usuarioData["id"];
        $_SESSION["usuario_nombre"] = $usuarioData["nombre"];
        header("Location: principal.php");
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
