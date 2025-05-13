<?php
session_start();
require "backend/config/database.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST["username"];
    $contrasena = $_POST["password"];

    // Buscar al usuario por nombre de usuario
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $usuarioData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuarioData && password_verify($contrasena, $usuarioData["contrasena"])) {
        // Guardamos en sesión
        $_SESSION["usuario_id"] = $usuarioData["id"];
        $_SESSION["usuario_nombre"] = $usuarioData["nombre"];
        
        // Redirigimos a la página principal
        header("Location: principal.php");
        exit;
    } else {
        $mensaje = "❌ Usuario o contraseña incorrectos.";
    }
}
?>

<!-- Puedes mostrar el mensaje en la misma página si quieres -->
<?php if ($mensaje): ?>
    <p style="color:red;"><?= $mensaje ?></p>
<?php endif; ?>
