<?php

$conn = new mysqli(
    "localhost",
    "root",
    "",
    "barberconnect_web"
);

if ($conn->connect_error) {
    die("Error DB: " . $conn->connect_error);
}