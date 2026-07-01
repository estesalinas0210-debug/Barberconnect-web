<?php

session_start();
include("../config/db.php");

$user_id = $_SESSION['user']['id'];

$stmt = $conn->prepare(
"
UPDATE notifications
SET is_read = 1
WHERE user_id=?
"
);

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

echo json_encode([
    "status"=>"success"
]);