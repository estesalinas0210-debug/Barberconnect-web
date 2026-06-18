<?php

session_start();
include("../config/db.php");

if(!isset($_SESSION['user'])){
    exit;
}

$client_id = $_SESSION['user']['id'];
$barber_id = $_POST['barber_id'];
$date = $_POST['date'];
$time = $_POST['time'];

$check = $conn->prepare(
"SELECT id
 FROM bookings
 WHERE barber_id=?
 AND booking_date=?
 AND booking_time=?
 AND status!='rejected'"
);

$check->bind_param("iis", $barber_id, $date, $time);
$check->execute();
$check_result = $check->get_result();

if($check_result->num_rows > 0){
    echo json_encode([
        "status"=>"error",
        "message"=>"Ya tienes una reserva para esa fecha y hora"
    ]);
    exit;
}

$stmt = $conn->prepare(
    "INSERT INTO bookings
    (client_id, barber_id, booking_date, booking_time)
    VALUES (?,?,?,?)"
);

$stmt->bind_param(
    "iiss",
    $client_id,
    $barber_id,
    $date,
    $time
);

if($stmt->execute()){
    echo json_encode([
        "status"=>"success"
    ]);
}else{
    echo json_encode([
        "status"=>"error"
    ]);
}