<?php
$host = "tournauticocoloniasz.gamer.gd"; // reemplazá con el host real
$username = "root";        // tu usuario de MySQL
$password = "";   // la contraseña de tu cuenta
$database = "if0_40224467_registro_usuarios"; // tu base exacta

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Error al conectar: " . mysqli_connect_error());
}
echo "Conexión exitosa!";
?>