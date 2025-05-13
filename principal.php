<?php
session_start();
if (!isset($_SESSION["usuario_id"])) {
    header("Location: index.php"); // Redirige a la página de inicio de sesión si no hay sesión activa
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página Principal</title>
</head>
<body>
    <h1>Bienvenido, <?= $_SESSION["usuario_nombre"] ?>!</h1>
    <a href="logout.php">Cerrar sesión</a>
</body>
</html>
