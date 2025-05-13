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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comercio Principal</title>
    <link rel="stylesheet" href="styles/principal.css">
</head>
<body>

    <header>
        <div class="container">
            <h1>Mi Comercio</h1>
            <nav>
                <ul>
                    <li><a href="#">Inicio</a></li>
                    <li><a href="#">Productos</a></li>
                    <li><a href="#">Servicios</a></li>
                    <li><a href="#">Contacto</a></li>
                    <li><a href="index.php">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h2>Bienvenido a tu tienda de confianza</h2>
            <p>Descubrí los mejores productos al mejor precio</p>
        </div>
    </section>

    <section class="features">
        <div class="container">
            <h2>Nuestras Ventajas</h2>
            <div class="cards">
                <div class="card">
                    <h3>Envíos Rápidos</h3>
                    <p>Entregamos en menos de 48 horas en todo el país.</p>
                </div>
                <div class="card">
                    <h3>Pagos Seguros</h3>
                    <p>Trabajamos con plataformas de pago confiables y seguras.</p>
                </div>
                <div class="card">
                    <h3>Atención Personalizada</h3>
                    <p>Estamos disponibles para ayudarte por WhatsApp y correo.</p>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 Mi Comercio. Todos los derechos reservados.</p>
    </footer>

</body>
</html>
