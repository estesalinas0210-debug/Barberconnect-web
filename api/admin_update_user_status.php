<?php

session_start();

header('Content-Type: application/json; charset=utf-8');

include("../config/db.php");


/* =========================
   SEGURIDAD
========================= */

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


/* =========================
   DATOS
========================= */

$user_id = intval($_POST['user_id'] ?? 0);
$new_status = $_POST['status'] ?? '';


if (!$user_id) {

    echo json_encode([
        "status" => "error",
        "message" => "Usuario inválido"
    ]);

    exit;
}


if (!in_array($new_status, ['active', 'blocked'])) {

    echo json_encode([
        "status" => "error",
        "message" => "Estado inválido"
    ]);

    exit;
}


/* =========================
   EVITAR BLOQUEAR ADMIN
========================= */

$stmt = $conn->prepare(
    "SELECT role
     FROM users
     WHERE id = ?
     LIMIT 1"
);

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();


if (!$user) {

    echo json_encode([
        "status" => "error",
        "message" => "Usuario no encontrado"
    ]);

    exit;
}


if ($user['role'] === 'admin') {

    echo json_encode([
        "status" => "error",
        "message" => "No puedes modificar el estado de un administrador"
    ]);

    exit;
}


/* =========================
   ACTUALIZAR
========================= */

$stmt = $conn->prepare(
    "UPDATE users
     SET status = ?
     WHERE id = ?"
);

$stmt->bind_param(
    "si",
    $new_status,
    $user_id
);


if ($stmt->execute()) {

    echo json_encode([
        "status" => "success",
        "message" =>
            $new_status === 'blocked'
            ? "Usuario bloqueado correctamente"
            : "Usuario activado correctamente"
    ]);

} else {

    echo json_encode([
        "status" => "error",
        "message" => "No se pudo actualizar el usuario"
    ]);

}