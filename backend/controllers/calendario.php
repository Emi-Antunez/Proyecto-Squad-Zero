<?php
require_once __DIR__ . '/../models/reserva.php';
require_once __DIR__ . '/../database/conexion.php';

$reservaModel = new Reserva($conn);

// Función para generar archivo ICS
function generarICS($idReserva) {
    global $reservaModel, $conn;

    // Obtener datos de la reserva
    $reserva = $reservaModel->obtenerPorId($idReserva);

    if (!$reserva) {
        http_response_code(404);
        echo json_encode(["error" => "Reserva no encontrada"]);
        return;
    }

    // Verificar que el usuario logueado es el propietario de la reserva
    if (!isset($_SESSION['id_usuario']) || $_SESSION['id_usuario'] != $reserva['id_usuario']) {
        http_response_code(403);
        echo json_encode(["error" => "No tienes permisos para acceder a esta reserva"]);
        return;
    }

    // Obtener datos del usuario
    require_once __DIR__ . '/../models/usuario.php';
    $usuarioModel = new Usuario($conn);
    $usuario = $usuarioModel->obtenerPorId($reserva['id_usuario']);

    // Crear contenido del archivo ICS
    $fechaHoraInicio = $reserva['fecha'] . ' ' . $reserva['hora'];
    $fechaHoraFin = date('Y-m-d H:i:s', strtotime($fechaHoraInicio . ' +2 hours')); // Duración aproximada del tour

    // Convertir a formato ICS (UTC)
    $dtstart = date('Ymd\THis\Z', strtotime($fechaHoraInicio));
    $dtend = date('Ymd\THis\Z', strtotime($fechaHoraFin));

    // Generar UID único
    $uid = uniqid('tour-nautico-') . '@tour-nautico-colonia.com';

    // Crear contenido ICS
    $icsContent = "BEGIN:VCALENDAR\r\n";
    $icsContent .= "VERSION:2.0\r\n";
    $icsContent .= "PRODID:-//Tour Náutico Colonia//Reservas//ES\r\n";
    $icsContent .= "CALSCALE:GREGORIAN\r\n";
    $icsContent .= "BEGIN:VEVENT\r\n";
    $icsContent .= "UID:{$uid}\r\n";
    $icsContent .= "DTSTART:{$dtstart}\r\n";
    $icsContent .= "DTEND:{$dtend}\r\n";
    $icsContent .= "SUMMARY:Tour Náutico - {$reserva['tour']}\r\n";
    $icsContent .= "DESCRIPTION:Tour náutico en barco por Colonia del Sacramento\\n";
    $icsContent .= "Tour: {$reserva['tour']}\\n";
    $icsContent .= "Fecha: " . date('d/m/Y', strtotime($reserva['fecha'])) . "\\n";
    $icsContent .= "Hora: {$reserva['hora']}\\n";
    $icsContent .= "Personas: {$reserva['cantidad_personas']}\\n";
    $icsContent .= "Reservado por: {$usuario['nombre']} {$usuario['apellido']}\\n\\n";
    $icsContent .= "Información de contacto:\\n";
    $icsContent .= "WhatsApp: +598 96 018 202\\n";
    $icsContent .= "Email: squadzero1234@gmail.com\r\n";
    $icsContent .= "LOCATION:Puerto de Colonia del Sacramento\\n";
    $icsContent .= "ORGANIZER;CN=Tour Náutico Colonia:mailto:squadzero1234@gmail.com\r\n";
    $icsContent .= "STATUS:CONFIRMED\r\n";
    $icsContent .= "END:VEVENT\r\n";
    $icsContent .= "END:VCALENDAR\r\n";

    // Configurar headers para descarga
    header('Content-Type: text/calendar; charset=utf-8');
    header('Content-Disposition: attachment; filename="tour-nautico-' . $idReserva . '.ics"');
    header('Content-Length: ' . strlen($icsContent));
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');

    // Enviar contenido del archivo
    echo $icsContent;
    exit;
}
?>
