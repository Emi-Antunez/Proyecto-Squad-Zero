<?php
session_start();
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contacto</title>
    <link rel="stylesheet" href="../styles/contacto.css">
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

<main class="contacto-container">
    <h2>Contacto</h2>
    <p>Podés comunicarte con nosotros a través de nuestras redes sociales:</p>

    <div class="redes">
        <a href="https://www.instagram.com/" target="_blank" class="red">
            <img src="images/instagram.png" alt="Instagram"> Instagram
        </a>
        <a href="https://www.facebook.com/" target="_blank" class="red">
            <img src="images/facebook.png" alt="Facebook"> Facebook
        </a>
        <a href="https://wa.me/5491112345678" target="_blank" class="red">
            <img src="images/whatsapp.png" alt="WhatsApp"> WhatsApp
        </a>
    </div>
</main>

<footer>
    <p>&copy; 2025 Mi Comercio. Todos los derechos reservados.</p>
</footer>

</body>
</html>
