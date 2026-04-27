<?php
session_start();
require "config/DataBase.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: views/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: views/institutions.php");
    exit();
}

$student_id = $_SESSION["user_id"];
$institution_id = (int) $_POST["institution_id"];
$content = trim($_POST["content"]);

if (empty($content)) {
    header("Location: views/institution_detail.php?id=$institution_id&review_error=L'avis ne peut pas être vide");
    exit();
}

// Check if already reviewed
$check = $pdo->prepare("SELECT id FROM reviews WHERE student_id = ? AND institution_id = ?");
$check->execute([$student_id, $institution_id]);

if ($check->fetch()) {
    header("Location: views/institution_detail.php?id=$institution_id&review_error=Tu as déjà laissé un avis");
    exit();
}

$sql = "INSERT INTO reviews (student_id, institution_id, content, status) VALUES (?, ?, ?, 'pending')";
$stmt = $pdo->prepare($sql);
$stmt->execute([$student_id, $institution_id, $content]);

header("Location: views/institution_detail.php?id=$institution_id&review_success=1");
exit();
?>
