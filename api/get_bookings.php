<?php
session_start();
include("../config/db.php");

$barber_id = $_SESSION['user']['id'];

$stmt = $conn->prepare(
"SELECT bookings.*, users.name AS client_name
 FROM bookings
 JOIN users ON bookings.client_id = users.id
 WHERE barber_id=?
 ORDER BY booking_date DESC"
);

$stmt->bind_param("i",$barber_id);
$stmt->execute();

$result = $stmt->get_result();

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);