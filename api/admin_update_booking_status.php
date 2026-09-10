<?php

session_start();

header('Content-Type: application/json; charset=utf-8');

include("../config/db.php");

if (!isset($_SESSION['user'])) {
    echo json_encode([
        "status" => "error",
        "message" => "No autorizado"
    ]);
    exit;
}

if ($_SESSION['user']['role'] !== 'admin') {
    echo json_encode([
        "status" => "error",
        "message" => "Acceso denegado"
    ]);
    exit;
}

$booking_id = intval($_POST['booking_id'] ?? 0);
$new_status = $_POST['status'] ?? '';

if ($booking_id <= 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Reserva inválida"
    ]);
    exit;
}

$allowed_statuses = [
    'pending',
    'accepted',
    'rejected',
    'cancelled'
];

if (!in_array($new_status, $allowed_statuses, true)) {
    echo json_encode([
        "status" => "error",
        "message" => "Estado inválido"
    ]);
    exit;
}

$stmt = $conn->prepare("
    SELECT id
    FROM bookings
    WHERE id = ?
    LIMIT 1
");

if (!$stmt) {
    echo json_encode([
        "status" => "error",
        "message" => "Error SQL",
        "mysql_error" => $conn->error
    ]);
    exit;
}

$stmt->bind_param("i", $booking_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        "status" => "error",
        "message" => "La reserva no existe"
    ]);
    exit;
}

$stmt->close();

$stmt = $conn->prepare("
    UPDATE bookings
    SET status = ?
    WHERE id = ?
");

if (!$stmt) {
    echo json_encode([
        "status" => "error",
        "message" => "Error al preparar actualización",
        "mysql_error" => $conn->error
    ]);
    exit;
}

$stmt->bind_param("si", $new_status, $booking_id);

if ($stmt->execute()) {

    $messages = [
        "pending"   => "Reserva marcada como pendiente",
        "accepted"  => "Reserva aceptada correctamente",
        "rejected"  => "Reserva rechazada correctamente",
        "cancelled" => "Reserva cancelada correctamente"
    ];

    echo json_encode([
        "status" => "success",
        "message" => $messages[$new_status]
    ]);

} else {

    echo json_encode([
        "status" => "error",
        "message" => "No se pudo actualizar la reserva",
        "mysql_error" => $stmt->error
    ]);
}