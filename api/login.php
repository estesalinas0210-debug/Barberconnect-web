<?php

session_start();
include("../config/db.php");

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$result = $conn->query(
    "SELECT * FROM users WHERE email='$email'"
);

$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {

    $_SESSION['user'] = $user;

    echo json_encode([
        "status" => "ok",
        "role" => $user['role']
    ]);

} else {

    echo json_encode([
        "status" => "error"
    ]);

}