<?php

session_start();
include("../config/db.php");

$user_id = $_SESSION['user']['id'];

$sql = "
SELECT
booking_date,
booking_time,
status
FROM bookings
WHERE client_id='$user_id'
";

$res = $conn->query($sql);

$events = [];

while($row = $res->fetch_assoc()){

    $color = "#f39c12";

    if($row['status']=="accepted")
        $color="#27ae60";

    if($row['status']=="rejected")
        $color="#e74c3c";

    $events[] = [
      "title"=>$row['status'],
      "start"=>$row['booking_date']."T".$row['booking_time'],
      "color"=>$color
    ];

}

echo json_encode($events);