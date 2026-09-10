<?php

session_start();

header('Content-Type: application/json; charset=utf-8');

include("../config/db.php");


// Verificar sesión
if (!isset($_SESSION['user'])) {
    echo json_encode([
        "status" => "error",
        "message" => "No autorizado"
    ]);
    exit;
}


// Verificar administrador
if ($_SESSION['user']['role'] !== 'admin') {
    echo json_encode([
        "status" => "error",
        "message" => "Acceso denegado"
    ]);
    exit;
}


// CLIENTES
$result = $conn->query(
    "SELECT COUNT(*) AS total
     FROM users
     WHERE role = 'client'"
);

$totalClientes = $result->fetch_assoc()['total'];


// BARBEROS
$result = $conn->query(
    "SELECT COUNT(*) AS total
     FROM users
     WHERE role = 'barber'"
);

$totalBarberos = $result->fetch_assoc()['total'];


// RESERVAS PENDIENTES
$result = $conn->query(
    "SELECT COUNT(*) AS total
     FROM bookings
     WHERE status = 'pending'"
);

$totalPendientes = $result->fetch_assoc()['total'];


// RESERVAS CONFIRMADAS
$result = $conn->query(
    "SELECT COUNT(*) AS total
     FROM bookings
     WHERE status = 'accepted'"
);

$totalConfirmadas = $result->fetch_assoc()['total'];


// RESPUESTA

echo json_encode([
    "status" => "success",
    "clientes" => (int)$totalClientes,
    "barberos" => (int)$totalBarberos,
    "pendientes" => (int)$totalPendientes,
    "confirmadas" => (int)$totalConfirmadas
]);