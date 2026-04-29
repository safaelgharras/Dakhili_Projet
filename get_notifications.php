<?php
session_start();
require "config/DataBase.php";

if (!isset($_SESSION["user_id"])) {
    echo json_encode([]);
    exit();
}

$userId = $_SESSION["user_id"];

function time_ago($timestamp) {
    $time_ago = strtotime($timestamp);
    $current_time = time();
    $time_difference = $current_time - $time_ago;
    $seconds = $time_difference;
    $minutes      = round($seconds / 60);           // value 60 is seconds
    $hours           = round($seconds / 3600);           //value 3600 is 60 minutes * 60 sec
    $days          = round($seconds / 86400);          //86400 = 24 * 60 * 60;
    $weeks          = round($seconds / 604800);          // 7*24*60*60;
    $months          = round($seconds / 2629440);     //((365+365+365+365+366)/5/12)*24*60*60
    $years          = round($seconds / 31553280);     //(365+365+365+365+366)/5 * 24 * 60 * 60

    if($seconds <= 60) {
        return "À l'instant";
    } else if($minutes <= 60) {
        return $minutes == 1 ? "Il y a 1 minute" : "Il y a $minutes minutes";
    } else if($hours <= 24) {
        return $hours == 1 ? "Il y a 1 heure" : "Il y a $hours heures";
    } else if($days <= 7) {
        return $days == 1 ? "Hier" : "Il y a $days jours";
    } else if($weeks <= 4.3) {
        return $weeks == 1 ? "Il y a 1 semaine" : "Il y a $weeks semaines";
    } else if($months <= 12) {
        return $months == 1 ? "Il y a 1 mois" : "Il y a $months mois";
    } else {
        return $years == 1 ? "Il y a 1 an" : "Il y a $years ans";
    }
}

try {
    $stmt = $pdo->prepare("SELECT * FROM notifications WHERE student_id = ? ORDER BY created_at DESC LIMIT 20");
    $stmt->execute([$userId]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($notifications as &$n) {
        $n['time_ago'] = time_ago($n['created_at']);
    }

    header('Content-Type: application/json');
    echo json_encode($notifications);
} catch (Exception $e) {
    echo json_encode([]);
}
?>
