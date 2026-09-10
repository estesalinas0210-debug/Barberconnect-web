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

$name = trim($_POST['name'] ?? '');
$price = $_POST['price'] ?? '';
$duration = intval($_POST['duration'] ?? 0);

if ($name === '') {
    echo json_encode([
        "status" => "error",
        "message" => "El nombre del servicio es obligatorio"
    ]);
    exit;
}

if ($price === '' || !is_numeric($price) || floatval($price) < 0) {
    echo json_encode([
        "status" => "error",
        "message" => "El precio no es válido"
    ]);
    exit;
}

if ($duration <= 0) {
    echo json_encode([
        "status" => "error",
        "message" => "La duración debe ser mayor que 0"
    ]);
    exit;
}

$price = floatval($price);

$stmt = $conn->prepare("
    INSERT INTO services
    (name, price, duration)
    VALUES (?, ?, ?)
");

if (!$stmt) {
    echo json_encode([
        "status" => "error",
        "message" => "Error SQL",
        "mysql_error" => $conn->error
    ]);
    exit;
}

$stmt->bind_param(
    "sdi",
    $name,
    $price,
    $duration
);

if ($stmt->execute()) {

    echo json_encode([
        "status" => "success",
        "message" => "Servicio creado correctamente",
        "id" => $stmt->insert_id
    ]);

} else {

    echo json_encode([
        "status" => "error",
        "message" => "No se pudo crear el servicio",
        "mysql_error" => $stmt->error
    ]);
}