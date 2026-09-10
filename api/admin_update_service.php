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

$id = intval($_POST['id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$price = $_POST['price'] ?? '';
$duration = intval($_POST['duration'] ?? 0);

if ($id <= 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Servicio inválido"
    ]);
    exit;
}

if ($name === '') {
    echo json_encode([
        "status" => "error",
        "message" => "El nombre es obligatorio"
    ]);
    exit;
}

if ($price === '' || !is_numeric($price) || floatval($price) < 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Precio inválido"
    ]);
    exit;
}

if ($duration <= 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Duración inválida"
    ]);
    exit;
}

$price = floatval($price);

$stmt = $conn->prepare("
    UPDATE services
    SET name = ?, price = ?, duration = ?
    WHERE id = ?
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
    "sdii",
    $name,
    $price,
    $duration,
    $id
);

if ($stmt->execute()) {

    echo json_encode([
        "status" => "success",
        "message" => "Servicio actualizado correctamente"
    ]);

} else {

    echo json_encode([
        "status" => "error",
        "message" => "No se pudo actualizar",
        "mysql_error" => $stmt->error
    ]);
}