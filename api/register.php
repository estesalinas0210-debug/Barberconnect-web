<?php

header('Content-Type: application/json');

include("../config/db.php");

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);
$role = trim($_POST['role']);

$passwordHash = password_hash(
    $password,
    PASSWORD_DEFAULT
);
$check = $conn->prepare(
    "SELECT id FROM users WHERE email=?"
);

$check->bind_param("s", $email);
$check->execute();

$result = $check->get_result();

if ($result->num_rows > 0) {

    echo json_encode([
        "status" => "error",
        "message" => "Este correo ya está registrado"
    ]);

    exit;
}
$stmt = $conn->prepare(
  "SELECT * FROM users WHERE email=?"
);

$stmt = $conn->prepare(
    "INSERT INTO users (name,email,password,role)
     VALUES (?,?,?,?)"
);

$stmt->bind_param(
    "ssss",
    $name,
    $email,
    $passwordHash,
    $role
);

if ($stmt->execute()) {

    echo json_encode([
        "status" => "success",
        "message" => "Cuenta creada correctamente"
    ]);

} else {

    echo json_encode([
        "status" => "error",
        "message" => $conn->error
    ]);

}