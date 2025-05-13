<?php
session_start();
require "./backend/config/database.php"; // Asegurate de que la ruta sea correcta

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST["username"];
    $contrasena = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = :usuario");
    $stmt->execute(["usuario" => $usuario]);
    $usuarioData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuarioData && password_verify($contrasena, $usuarioData["contrasena"])) {
        $_SESSION["usuario"] = $usuarioData["usuario"];
        $_SESSION["id_usuario"] = $usuarioData["id"];
        
        // ✅ Acá va el header
        header("Location: ./Page/Principal.php");
        exit; // Siempre se recomienda usar exit después de un header
    } else {
        echo "Usuario o contraseña incorrectos.";
    }
}
?>
