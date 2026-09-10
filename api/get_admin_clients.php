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


$sql = "
    SELECT
        id,
        name,
        email,
        status
    FROM users
    WHERE role = 'client'
    ORDER BY name ASC
";


$stmt = $conn->prepare($sql);


if (!$stmt) {

    echo json_encode([
        "status" => "error",
        "message" => "Error en la consulta SQL",
        "mysql_error" => $conn->error
    ]);

    exit;
}


$stmt->execute();

$result = $stmt->get_result();

$clients = [];


while ($row = $result->fetch_assoc()) {

    $clients[] = $row;

}


echo json_encode([
    "status" => "success",
    "data" => $clients
]);