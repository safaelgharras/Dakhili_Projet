<?php
session_start();
require "config/DataBase.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: views/login.php");
    exit();
}

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: views/institutions.php?error=Invalid school");
    exit();
}

$student_id = $_SESSION["user_id"];
$institution_id = (int) $_GET["id"];

// Check if already saved
$check = $pdo->prepare("SELECT id FROM saved_schools WHERE student_id = ? AND institution_id = ?");
$check->execute([$student_id, $institution_id]);

if ($check->fetch()) {
    header("Location: views/institutions.php?error=School already saved");
    exit();
}

$sql = "INSERT INTO saved_schools (student_id, institution_id)
        VALUES (?, ?)";

$stmt = $pdo->prepare($sql);
$stmt->execute([$student_id, $institution_id]);

header("Location: views/institutions.php?success=School saved successfully!");
exit();
?>