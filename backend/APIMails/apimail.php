<?php
/**
 * API de Envío de Correos
 * 
 * Endpoint para enviar correos de confirmación de reservas
 */

header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Manejar preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../lib/EmailService.php';
require_once __DIR__ . '/../models/reserva.php';
require_once __DIR__ . '/../models/usuario.php';
require_once __DIR__ . '/../database/conexion.php';

// Configurar logs de errores
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/email_errores.log');

/**
 * Envía un correo de confirmación de reserva
 */
function enviarCorreoConfirmacion() {
    global $conn;
    
    try {
        // Validar método
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new Exception('Método no permitido. Use POST.');
        }
        
        // Obtener datos del request
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (!$data) {
            throw new Exception('Datos inválidos o vacíos.');
        }
        
        // Validar campos requeridos
        $camposRequeridos = ['id_reserva', 'id_usuario'];
        foreach ($camposRequeridos as $campo) {
            if (!isset($data[$campo]) || empty($data[$campo])) {
                throw new Exception("El campo '{$campo}' es requerido.");
            }
        }
        
        $idReserva = intval($data['id_reserva']);
        $idUsuario = intval($data['id_usuario']);
        
        // Obtener datos de la reserva
        $reservaModel = new Reserva($conn);
        $reserva = $reservaModel->obtenerPorId($idReserva);
        
        if (!$reserva) {
            throw new Exception('Reserva no encontrada.');
        }
        
        // Verificar que la reserva pertenece al usuario
        if ($reserva['id_usuario'] != $idUsuario) {
            throw new Exception('La reserva no pertenece al usuario especificado.');
        }
        
        // Obtener datos del usuario
        $usuarioModel = new Usuario($conn);
        $usuario = $usuarioModel->obtenerPorId($idUsuario);
        
        if (!$usuario) {
            throw new Exception('Usuario no encontrado.');
        }
        
        // Validar que el usuario tenga email
        if (empty($usuario['gmail'])) {
            throw new Exception('El usuario no tiene un correo electrónico registrado.');
        }
        
        // Enviar correo
        $emailService = new EmailService();
        $resultado = $emailService->enviarConfirmacionReserva($reserva, $usuario);
        
        if ($resultado) {
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'mensaje' => 'Correo de confirmación enviado exitosamente',
                'destinatario' => $usuario['gmail']
            ]);
        } else {
            throw new Exception('No se pudo enviar el correo. Verifica la configuración SMTP.');
        }
        
    } catch (Exception $e) {
        error_log("Error en apimail.php: " . $e->getMessage());
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}

/**
 * Endpoint de prueba para verificar configuración
 */
function testearConfiguracion() {
    try {
        require_once __DIR__ . '/../config/email_config.php';
        
        $config = [
            'SMTP_HOST' => SMTP_HOST,
            'SMTP_PORT' => SMTP_PORT,
            'SMTP_SECURE' => SMTP_SECURE,
            'EMAIL_FROM' => EMAIL_FROM,
            'EMAIL_FROM_NAME' => EMAIL_FROM_NAME,
            'configurado' => (SMTP_USERNAME !== 'tu-email@gmail.com')
        ];
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'mensaje' => 'Configuración cargada',
            'config' => $config
        ]);
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}

// Enrutamiento
$action = isset($_GET['action']) ? $_GET['action'] : 'enviar';

switch ($action) {
    case 'enviar':
        enviarCorreoConfirmacion();
        break;
        
    case 'test':
        testearConfiguracion();
        break;
        
    default:
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Acción no válida. Use: enviar o test'
        ]);
}
?>