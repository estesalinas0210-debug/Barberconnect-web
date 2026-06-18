<?php

include("../config/db.php");

$res = $conn->query(
    "SELECT * FROM services ORDER BY price ASC"
);

$data = [];

while($row = $res->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);