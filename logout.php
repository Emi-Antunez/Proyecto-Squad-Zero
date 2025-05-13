<?php
session_start();
session_unset();
session_destroy();

// Evitar mostrar caché luego del logout
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

header("Location: index.php");
exit();
