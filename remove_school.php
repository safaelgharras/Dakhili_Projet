<?php
session_start();
require "config/DataBase.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: views/login.php");
    exit();
}

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: views/saved_schools.php?error=Invalid school");
    exit();
}

$student_id = $_SESSION["user_id"];
$institution_id = (int) $_GET["id"];

$sql = "DELETE FROM saved_schools WHERE student_id = ? AND institution_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$student_id, $institution_id]);

header("Location: views/saved_schools.php?success=School removed successfully");
exit();
?>
