<?php

session_start();
include("../config/db.php");

$user_id =
$_SESSION['user']['id'];

$stmt = $conn->prepare(
"
SELECT *
FROM notifications
WHERE user_id=?
ORDER BY created_at DESC
"
);

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$result =
$stmt->get_result();

$data = [];

while(
$row = $result->fetch_assoc()
){
    $data[] = $row;
}

echo json_encode($data);