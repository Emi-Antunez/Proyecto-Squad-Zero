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
    global $reservaModel;
    if ($reservaModel->existeReserva($tour, $fecha, $hora)) {
        echo json_encode(["error" => "Ya existe una reserva para ese tour, fecha y hora."]);
        exit;
    }
    if ($reservaModel->agregar($id_usuario, $tour, $fecha, $hora, $cantidad_personas)) {
        echo json_encode(["mensaje" => "Reserva agregada"]);
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
    if ($reservaModel->eliminar($id)) {
        echo json_encode(["mensaje" => "Reserva eliminada", "id" => $id]);
    } else {
        echo json_encode(["error" => "No se pudo eliminar"]);
    }
}
?>