<?php

session_start();
include("../config/db.php");

$barber_id = $_SESSION['user']['id'];

$photo = "";
$bio = $_POST['bio'];
$specialty = $_POST['specialty'];
$experience = $_POST['experience'];

$check = $conn->prepare(
"
SELECT id
FROM barber_profiles
WHERE barber_id=?
"
);

$check->bind_param("i",$barber_id);
$check->execute();

$result = $check->get_result();

if(isset($_FILES['photo'])){

    $fileName =
    time() . "_" .
    basename($_FILES['photo']['name']);

    $target =
    "../uploads/profiles/" .
    $fileName;

    move_uploaded_file(
        $_FILES['photo']['tmp_name'],
        $target
    );

    $photo =
    "uploads/profiles/" .
    $fileName;

}

if($result->num_rows > 0){

    $get = $conn->prepare(
    "SELECT photo
     FROM barber_profiles
     WHERE barber_id=?"
    );

    $get->bind_param("i",$barber_id);
    $get->execute();

    $row = $get->get_result()->fetch_assoc();

    if(empty($photo)){
        $photo = $row['photo'];
    }

    $stmt = $conn->prepare(
    "
    UPDATE barber_profiles
    SET
      photo=?,
      bio=?,
      specialty=?,
      experience=?
    WHERE barber_id=?
    "
    );

    $stmt->bind_param(
        "sssii",
        $photo,
        $bio,
        $specialty,
        $experience,
        $barber_id
    );

}
$stmt->execute();

echo json_encode([
    "status"=>"success",
    "message"=>"Perfil actualizado correctamente"
]);