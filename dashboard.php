<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: http://localhost/Proyecto-Squad-Zero/backend/controllers/login.php");
    exit();
}
?>

<h1>Bienvenido, <?php echo $_SESSION["usuario"]; ?>!</h1>
<a href="logout.php">Cerrar sesión</a>
