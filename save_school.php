<?php
session_start();
require "config/DataBase.php";


if (!isset($_SESSION["user_id"])) {
    header("Location: views/login.php");
    exit();
}


$student_id = $_SESSION["user_id"];
$institution_id = $_GET["id"];


$sql = "INSERT INTO saved_schools (student_id, institution_id)
        VALUES (?, ?)";

$stmt = $pdo->prepare($sql);
$stmt->execute([$student_id, $institution_id]);

echo "School saved successfully ";
?>