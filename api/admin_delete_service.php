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

if ($id <= 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Servicio inválido"
    ]);
    exit;
}

/*
 * Comprobar si existen reservas relacionadas
 */

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM bookings
    WHERE service_id = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (intval($row['total']) > 0) {

    echo json_encode([
        "status" => "error",
        "message" => "No puedes eliminar este servicio porque tiene reservas asociadas. Puedes editarlo en lugar de eliminarlo."
    ]);

    exit;
}

/*
 * Eliminar
 */

$stmt = $conn->prepare("
    DELETE FROM services
    WHERE id = ?
");

$stmt->bind_param("i", $id);

if ($stmt->execute()) {

    echo json_encode([
        "status" => "success",
        "message" => "Servicio eliminado correctamente"
    ]);

} else {

    echo json_encode([
        "status" => "error",
        "message" => "No se pudo eliminar el servicio",
        "mysql_error" => $stmt->error
    ]);
}