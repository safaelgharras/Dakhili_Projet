<?php
session_start();
require "config/DataBase.php";

if (!isset($_SESSION["user_id"])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$userId = $_SESSION["user_id"];
$notifId = isset($_GET['id']) ? (int)$_GET['id'] : null;
$all = isset($_GET['all']) ? (int)$_GET['all'] : 0;

try {
    if ($all === 1) {
        $stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE student_id = ?");
        $stmt->execute([$userId]);
    } else if ($notifId) {
        $stmt = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND student_id = ?");
        $stmt->execute([$notifId, $userId]);
    }

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
