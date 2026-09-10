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


$user_id = intval($_POST['user_id'] ?? 0);


if (!$user_id) {

    echo json_encode([
        "status" => "error",
        "message" => "Usuario inválido"
    ]);

    exit;
}


/* =========================
   COMPROBAR USUARIO
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


/* =========================
   PROTEGER ADMIN
========================= */

if ($user['role'] === 'admin') {

    echo json_encode([
        "status" => "error",
        "message" => "No puedes eliminar un administrador"
    ]);

    exit;
}


/* =========================
   ELIMINAR
========================= */

$stmt = $conn->prepare(
    "DELETE FROM users
     WHERE id = ?"
);

$stmt->bind_param("i", $user_id);


if ($stmt->execute()) {

    echo json_encode([
        "status" => "success",
        "message" => "Usuario eliminado correctamente"
    ]);

} else {

    echo json_encode([
        "status" => "error",
        "message" => "No se pudo eliminar el usuario"
    ]);

}