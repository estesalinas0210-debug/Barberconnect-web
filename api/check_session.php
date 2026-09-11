<?php

session_start();

header('Content-Type: application/json; charset=utf-8');

include("../config/db.php");


// ==========================================
// COMPROBAR SESIÓN
// ==========================================

if (!isset($_SESSION['user']['id'])) {

    echo json_encode([
        "status" => "error",
        "message" => "Sesión no iniciada",
        "logout" => true
    ]);

    exit;
}


$user_id = intval($_SESSION['user']['id']);


// ==========================================
// COMPROBAR ESTADO REAL EN MYSQL
// ==========================================

$stmt = $conn->prepare(
    "SELECT id, name, email, role, status
     FROM users
     WHERE id = ?
     LIMIT 1"
);

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();


// ==========================================
// USUARIO YA NO EXISTE
// ==========================================

if (!$user) {

    session_unset();
    session_destroy();

    echo json_encode([
        "status" => "error",
        "message" => "La cuenta ya no existe",
        "logout" => true
    ]);

    exit;
}


// ==========================================
// CUENTA BLOQUEADA
// ==========================================

if ($user['status'] === 'blocked') {

    session_unset();
    session_destroy();

    echo json_encode([
        "status" => "error",
        "message" => "Tu cuenta ha sido bloqueada por la administración.",
        "logout" => true
    ]);

    exit;
}


// ==========================================
// ACTUALIZAR DATOS DE SESIÓN
// ==========================================

$_SESSION['user']['name'] = $user['name'];
$_SESSION['user']['email'] = $user['email'];
$_SESSION['user']['role'] = $user['role'];
$_SESSION['user']['status'] = $user['status'];


// ==========================================
// TODO CORRECTO
// ==========================================

echo json_encode([
    "status" => "success",
    "user" => $user
]);

?>