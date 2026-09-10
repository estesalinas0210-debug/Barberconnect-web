<?php

session_start();

header('Content-Type: application/json; charset=utf-8');

include("../config/db.php");


if (!isset($_SESSION['user'])) {
    echo json_encode([
        "status" => "error",
        "message" => "No autorizado"
    ]);
    exit;
}


if ($_SESSION['user']['role'] !== 'admin') {
    echo json_encode([
        "status" => "error",
        "message" => "Acceso denegado"
    ]);
    exit;
}


$result = $conn->query(
    "SELECT
        id,
        name,
        price,
        duration
     FROM services
     ORDER BY name ASC"
);


$services = [];


while ($row = $result->fetch_assoc()) {

    $services[] = $row;

}


echo json_encode([
    "status" => "success",
    "data" => $services
]);