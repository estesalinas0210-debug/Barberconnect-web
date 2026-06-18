<?php

session_start();
include("../config/db.php");

$client_id = $_SESSION['user']['id'];

$stmt = $conn->prepare(
"SELECT bookings.*, users.name AS barber_name
 FROM bookings
 JOIN users ON bookings.barber_id = users.id
 WHERE client_id=?
 ORDER BY booking_date DESC"
);

$stmt->bind_param("i", $client_id);
$stmt->execute();

$result = $stmt->get_result();

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);