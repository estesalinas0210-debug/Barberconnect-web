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


$stmt = $conn->prepare(
    "SELECT

        users.id,
        users.name,
        users.email,
        users.status,

        barber_profiles.photo,
        barber_profiles.bio,
        barber_profiles.specialty,
        barber_profiles.experience

     FROM users

     LEFT JOIN barber_profiles
     ON barber_profiles.barber_id = users.id

     WHERE users.role = 'barber'

     ORDER BY users.name ASC"
);


if (!$stmt) {

    echo json_encode([
        "status" => "error",
        "message" => "Error SQL",
        "mysql_error" => $conn->error
    ]);

    exit;
}


$stmt->execute();

$result = $stmt->get_result();

$barbers = [];


while ($row = $result->fetch_assoc()) {

    $barbers[] = $row;

}


echo json_encode([
    "status" => "success",
    "data" => $barbers
]);