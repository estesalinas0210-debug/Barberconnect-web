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

require_once("create_notification.php");

$get = $conn->prepare(
"
SELECT client_id
FROM bookings
WHERE id=?
"
);

$get->bind_param("i",$id);
$get->execute();

$booking =
$get->get_result()->fetch_assoc();

$client_id =
$booking['client_id'];

if($status == "accepted"){

    createNotification(
        $conn,
        $client_id,
        "✅ Tu reserva fue aceptada"
    );

}

if($status == "rejected"){

    createNotification(
        $conn,
        $client_id,
        "❌ Tu reserva fue rechazada"
    );

}