<?php

session_start();
include("../config/db.php");

if(!isset($_SESSION['user'])){
    exit;
}

$id = $_POST['id'];

$stmt = $conn->prepare(
"
UPDATE bookings
SET status='cancelled'
WHERE id=?
"
);

$stmt->bind_param("i", $id);

if($stmt->execute()){

    echo json_encode([
        "status"=>"success"
    ]);

}else{

    echo json_encode([
        "status"=>"error"
    ]);

}

$stmt = $conn->prepare(
"
UPDATE bookings
SET
    status='cancelled',
    cancelled_at=NOW()
WHERE id=?
"
);