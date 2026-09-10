<?php

session_start();

header('Content-Type: application/json; charset=utf-8');

include("../config/db.php");

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';


// Validar datos
if (empty($email) || empty($password)) {

    echo json_encode([
        "status" => "error",
        "message" => "Completa todos los campos"
    ]);

    exit;
}


// Buscar usuario de forma segura
$stmt = $conn->prepare(
    "SELECT *
     FROM users
     WHERE email = ?
     LIMIT 1"
);

$stmt->bind_param("s", $email);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();


if ($user && password_verify($password, $user['password'])) {

    // Crear sesión
    $_SESSION['user'] = $user;


    // Determinar destino
    if ($user['role'] === 'admin') {

        $redirect = 'admin/dashboard.php';

    } elseif ($user['role'] === 'barber') {

        $redirect = 'barber/dashboard.php';

    } else {

        $redirect = 'client/dashboard.php';

    }


    echo json_encode([
        "status" => "ok",
        "role" => $user['role'],
        "redirect" => $redirect
    ]);

} else {

    echo json_encode([
        "status" => "error",
        "message" => "Correo o contraseña incorrectos"
    ]);

}