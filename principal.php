<?php
session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: index.php");
    exit;
}

// Cabeceras para evitar caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 1 Jan 2000 00:00:00 GMT");

$nombre = $_SESSION["usuario_nombre"];
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comercio Principal</title>
    <link rel="stylesheet" href="styles/principal.css">

        <script>
    // Si el usuario vuelve con "Atrás" después de cerrar sesión,
    // forzamos recarga de la página para que PHP verifique la sesión
    window.addEventListener('pageshow', function(event) {
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            window.location.reload();
        }
    });
    </script>

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
                    <li><a href="logout.php">Cerrar Sesión</a></li>
 
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
                    <p>Entregamos en menos de 12 horas en todo colonia.</p>
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
