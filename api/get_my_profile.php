<?php

session_start();
include("../config/db.php");

$barber_id = $_SESSION['user']['id'];

$stmt = $conn->prepare(
"
SELECT *
FROM barber_profiles
WHERE barber_id=?
"
);

$stmt->bind_param("i", $barber_id);
$stmt->execute();

$result = $stmt->get_result();

echo json_encode(
    $result->fetch_assoc()
);