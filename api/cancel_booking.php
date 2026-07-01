<?php

session_start();
include("../config/db.php");

if(!isset($_SESSION['user'])){
    exit;
}

$id = $_POST['id'];

$stmt = $conn->prepare(
"
UPDATE bookings
SET status='cancelled'
WHERE id=?
"
);

$stmt->bind_param("i", $id);

if($stmt->execute()){

    echo json_encode([
        "status"=>"success"
    ]);

}else{

    echo json_encode([
        "status"=>"error"
    ]);

}

$stmt = $conn->prepare(
"
UPDATE bookings
SET
    status='cancelled',
    cancelled_at=NOW()
WHERE id=?
"
);

createNotification(
    $conn,
    $client_id,
    "🚫 Reserva cancelada correctamente"
);

$get = $conn->prepare(
"
SELECT barber_id
FROM bookings
WHERE id=?
"
);

$get->bind_param("i",$booking_id);
$get->execute();

$row =
$get->get_result()->fetch_assoc();

$barber_id =
$row['barber_id'];

createNotification(
    $conn,
    $barber_id,
    "❌ Un cliente canceló una reserva"
);

createNotification(
    $conn,
    $barber_id,
    "🔄 Un cliente cambió la fecha de su reserva"
);