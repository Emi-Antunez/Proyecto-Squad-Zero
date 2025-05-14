<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Servicios</title>
    <link rel="stylesheet" href="styles/servicios.css">
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

<h2 class="titulo">Nuestros Servicios</h2>

<div class="servicios-container">
    <div class="servicio-card">
        <img src="images/servicio1.jpg" alt="Servicio 1">
        <h3>Asesoramiento personalizado</h3>
        <p>Te ayudamos a elegir los productos que mejor se adapten a tus necesidades.</p>
    </div>
    <div class="servicio-card">
        <img src="images/servicio2.jpg" alt="Servicio 2">
        <h3>Envíos rápidos</h3>
        <p>Contamos con envíos en 24hs para garantizarte una experiencia fluida.</p>
    </div>
    <div class="servicio-card">
        <img src="images/servicio3.jpg" alt="Servicio 3">
        <h3>Soporte técnico</h3>
        <p>¿Problemas con tu compra? Nuestro equipo técnico está para ayudarte.</p>
    </div>
</div>

<footer>
    <p>&copy; 2025 Mi Comercio. Todos los derechos reservados.</p>
</footer>

</body>
</html>
