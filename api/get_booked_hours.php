<?php

include("../config/db.php");

$barber_id = $_GET['barber_id'];
$date = $_GET['date'];

$stmt = $conn->prepare(
"SELECT booking_time
 FROM bookings
 WHERE barber_id=?
 AND booking_date=?
 AND status NOT IN ('rejected','cancelled')"
);

$stmt->bind_param(
"is",
$barber_id,
$date
);

$stmt->execute();

$result = $stmt->get_result();

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row['booking_time'];
}

echo json_encode($data);