<?php

include("../config/db.php");

$result = $conn->query(
    "SELECT id,name FROM users WHERE role='barber'"
);

$barbers = [];

while($row = $result->fetch_assoc()){
    $barbers[] = $row;
}

echo json_encode($barbers);