<?php
include("../config/db.php");

$barber_id = $_GET['barber_id'];
$date = $_GET['date'];

$res = $conn->query("
  SELECT time FROM bookings 
  WHERE barber_id='$barber_id' 
  AND date='$date'
  AND status != 'rejected'
");

$times = [];

while ($row = $res->fetch_assoc()) {
  $times[] = $row['time'];
}

echo json_encode($times);
?>