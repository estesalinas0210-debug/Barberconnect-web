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

$sql = "
    SELECT
        bookings.id,
        bookings.booking_date,
        bookings.booking_time,
        bookings.status,
        bookings.created_at,

        clients.id AS client_id,
        clients.name AS client_name,
        clients.email AS client_email,

        barbers.id AS barber_id,
        barbers.name AS barber_name,

        services.id AS service_id,
        services.name AS service_name,
        services.price

    FROM bookings

    JOIN users AS clients
        ON bookings.client_id = clients.id

    JOIN users AS barbers
        ON bookings.barber_id = barbers.id

    LEFT JOIN services
        ON bookings.service_id = services.id

    ORDER BY
        bookings.booking_date DESC,
        bookings.booking_time DESC
";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode([
        "status" => "error",
        "message" => "Error al consultar reservas",
        "mysql_error" => $conn->error
    ]);
    exit;
}

$bookings = [];

while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

echo json_encode([
    "status" => "success",
    "data" => $bookings
]);