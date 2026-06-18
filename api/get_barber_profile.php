<?php

include("../config/db.php");

$id = $_GET['id'];

$stmt = $conn->prepare(
"SELECT *
 FROM barber_profiles
 WHERE barber_id=?"
);

$stmt->bind_param("i",$id);
$stmt->execute();

$result = $stmt->get_result();

echo json_encode(
    $result->fetch_assoc()
);