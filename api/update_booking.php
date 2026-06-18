<?php

session_start();
include("../config/db.php");

if (!isset($_SESSION['user'])) {
    echo json_encode([
        "status" => "error",
        "message" => "No autorizado"
    ]);
    exit;
}

$id = $_POST['id'];
$status = $_POST['status'];

$stmt = $conn->prepare(
    "UPDATE bookings
     SET status = ?
     WHERE id = ?"
);

$stmt->bind_param(
    "si",
    $status,
    $id
);

if ($stmt->execute()) {

    echo json_encode([
        "status" => "success",
        "message" => "Reserva actualizada"
    ]);

} else {

    echo json_encode([
        "status" => "error",
        "message" => $conn->error
    ]);

}