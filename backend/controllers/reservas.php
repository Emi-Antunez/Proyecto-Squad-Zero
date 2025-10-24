<?php
require_once __DIR__ . '/../models/reserva.php';
require_once __DIR__ . '/../database/conexion.php';

$reservaModel = new Reserva($conn);

function listarReservas() {
    global $reservaModel;
    echo json_encode($reservaModel->obtenerTodas());
}

function mostrarReserva($id) {
    global $reservaModel;
    $reserva = $reservaModel->obtenerPorId($id);
    if ($reserva) {
        echo json_encode($reserva);
    } else {
        echo json_encode(["error" => "Reserva no encontrada"]);
    }
}

function agregarReserva($id_usuario, $tour, $fecha, $hora, $cantidad_personas) {
    global $reservaModel, $conn;
    if ($reservaModel->existeReserva($tour, $fecha, $hora)) {
        echo json_encode(["error" => "Ya existe una reserva para ese tour, fecha y hora."]);
        exit;
    }
    if ($reservaModel->agregar($id_usuario, $tour, $fecha, $hora, $cantidad_personas)) {
        // Obtener el ID de la reserva recién creada
        $idReserva = $conn->insert_id;
        
        // Intentar enviar correo de confirmación
        try {
            require_once __DIR__ . '/../lib/EmailService.php';
            require_once __DIR__ . '/../models/usuario.php';
            
            $usuarioModel = new Usuario($conn);
            $usuario = $usuarioModel->obtenerPorId($id_usuario);
            
            if ($usuario && !empty($usuario['gmail'])) {
                $reserva = [
                    'id' => $idReserva,
                    'id_usuario' => $id_usuario,
                    'tour' => $tour,
                    'fecha' => $fecha,
                    'hora' => $hora,
                    'cantidad_personas' => $cantidad_personas
                ];
                
                $emailService = new EmailService();
                $emailEnviado = $emailService->enviarConfirmacionReserva($reserva, $usuario);
                
                echo json_encode([
                    "mensaje" => "Reserva agregada",
                    "id" => $idReserva,
                    "email_enviado" => $emailEnviado
                ]);
            } else {
                echo json_encode([
                    "mensaje" => "Reserva agregada",
                    "id" => $idReserva,
                    "email_enviado" => false,
                    "nota" => "No se pudo enviar el correo (usuario sin email)"
                ]);
            }
        } catch (Exception $e) {
            error_log("Error enviando email: " . $e->getMessage());
            echo json_encode([
                "mensaje" => "Reserva agregada",
                "id" => $idReserva,
                "email_enviado" => false,
                "nota" => "Error al enviar correo de confirmación"
            ]);
        }
    } else {
        echo json_encode(["error" => "No se pudo agregar"]);
    }
}

function modificarReserva($id, $id_usuario, $tour, $fecha, $hora, $cantidad_personas) {
    global $reservaModel;
    if ($reservaModel->modificar($id, $id_usuario, $tour, $fecha, $hora, $cantidad_personas)) {
        echo json_encode(["mensaje" => "Reserva modificada", "id" => $id]);
    } else {
        echo json_encode(["error" => "No se pudo modificar"]);
    }
}

function eliminarReserva($id, $motivo_cancelacion = '') {
    global $reservaModel, $conn;

    // Asegurar cabecera JSON y código de estado apropiado
    header('Content-Type: application/json; charset=utf-8');

    try {
        // Obtener datos de la reserva antes de eliminarla
        $reserva = $reservaModel->obtenerPorId($id);
        
        if (!$reserva) {
            http_response_code(404);
            echo json_encode([
                "success" => false,
                "error" => "Reserva no encontrada"
            ]);
            return;
        }
        
        // Obtener datos del usuario
        require_once __DIR__ . '/../models/usuario.php';
        $usuarioModel = new Usuario($conn);
        $usuario = $usuarioModel->obtenerPorId($reserva['id_usuario']);
        
        // Eliminar la reserva
        if ($reservaModel->eliminar($id)) {
            // Intentar enviar correo de cancelación
            $emailEnviado = false;
            if ($usuario && !empty($usuario['gmail'])) {
                try {
                    require_once __DIR__ . '/../lib/EmailService.php';
                    $emailService = new EmailService();
                    
                    // Si no hay motivo, usar uno por defecto
                    if (empty($motivo_cancelacion)) {
                        $motivo_cancelacion = 'La reserva ha sido cancelada por el administrador.';
                    }
                    
                    $emailEnviado = $emailService->enviarCorreoCancelacion($reserva, $usuario, $motivo_cancelacion);
                } catch (Exception $e) {
                    error_log("Error enviando email de cancelación: " . $e->getMessage());
                }
            }
            
            http_response_code(200);
            echo json_encode([
                "success" => true,
                "mensaje" => "Reserva eliminada",
                "id" => $id,
                "email_enviado" => $emailEnviado
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "error" => "No se pudo eliminar"
            ]);
        }
    } catch (Exception $e) {
        error_log("Error en eliminarReserva: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "error" => "Error al procesar la eliminación"
        ]);
    }
}
?>