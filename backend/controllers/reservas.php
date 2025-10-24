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

function eliminarReserva($id) {
    global $reservaModel;

    // Asegurar cabecera JSON y código de estado apropiado
    header('Content-Type: application/json; charset=utf-8');

    if ($reservaModel->eliminar($id)) {
        http_response_code(200);
        echo json_encode([
            "success" => true,
            "mensaje" => "Reserva eliminada",
            "id" => $id
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "error" => "No se pudo eliminar"
        ]);
    }
}
?>