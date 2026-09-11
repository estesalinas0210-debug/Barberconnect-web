<?php

session_start();

header('Content-Type: application/json; charset=utf-8');

include("../config/db.php");


// =====================================================
// DATOS DEL FORMULARIO
// =====================================================

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';


// =====================================================
// VALIDAR CAMPOS
// =====================================================

if ($email === '' || $password === '') {

    echo json_encode([
        "status" => "error",
        "message" => "Completa todos los campos"
    ]);

    exit;
}


// =====================================================
// BUSCAR USUARIO
// =====================================================

$stmt = $conn->prepare(
    "SELECT *
     FROM users
     WHERE email = ?
     LIMIT 1"
);

if (!$stmt) {

    echo json_encode([
        "status" => "error",
        "message" => "Error interno del servidor"
    ]);

    exit;
}

$stmt->bind_param("s", $email);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();


// =====================================================
// USUARIO NO EXISTE
// =====================================================

if (!$user) {

    echo json_encode([
        "status" => "error",
        "message" => "Correo o contraseña incorrectos"
    ]);

    exit;
}


// =====================================================
// VERIFICAR CONTRASEÑA
// =====================================================

if (!password_verify($password, $user['password'])) {

    echo json_encode([
        "status" => "error",
        "message" => "Correo o contraseña incorrectos"
    ]);

    exit;
}


// =====================================================
// VERIFICAR ESTADO DE LA CUENTA
// =====================================================

if (
    isset($user['status']) &&
    $user['status'] === 'blocked'
) {

    echo json_encode([
        "status" => "error",
        "message" => "Tu cuenta está bloqueada. Contacta con la administración."
    ]);

    exit;
}


// =====================================================
// CREAR SESIÓN
// =====================================================

$_SESSION['user'] = $user;


// =====================================================
// REDIRECCIÓN SEGÚN ROL
// =====================================================

if ($user['role'] === 'admin') {

    $redirect = 'admin/dashboard.php';

} elseif ($user['role'] === 'barber') {

    $redirect = 'barber/dashboard.php';

} else {

    $redirect = 'client/dashboard.php';

}


// =====================================================
// RESPUESTA
// =====================================================

echo json_encode([
    "status" => "ok",
    "role" => $user['role'],
    "redirect" => $redirect
]);

?>