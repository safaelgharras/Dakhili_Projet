<?php
session_start();
require "config/DataBase.php";

if (!isset($_SESSION["user_id"])) {
    header('Content-Type: application/json');
    echo json_encode([]);
    exit();
}

$userId = $_SESSION["user_id"];

function time_ago($timestamp) {
    $time_ago = strtotime($timestamp);
    $current_time = time();
    $time_difference = $current_time - $time_ago;
    $seconds = $time_difference;
    $minutes      = round($seconds / 60);
    $hours           = round($seconds / 3600);
    $days          = round($seconds / 84600);
    $weeks          = round($seconds / 604800);
    $months          = round($seconds / 2629440);
    $years          = round($seconds / 31553280);

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
    $sql = "SELECT n.*, 
                   COALESCE(un.is_read, 0) as is_read
            FROM notifications n
            LEFT JOIN user_notifications un ON n.id = un.notification_id AND un.user_id = ?
            WHERE (n.is_global = 1 OR n.target_user_id = ?)
            AND (un.is_deleted IS NULL OR un.is_deleted = 0)
            ORDER BY n.created_at DESC LIMIT 50";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId, $userId]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($notifications as &$n) {
        $n['time_ago'] = time_ago($n['created_at']);
        // Add icon based on type
        switch($n['type']) {
            case 'system': $n['icon'] = '⚙️'; break;
            case 'school': $n['icon'] = '🏫'; break;
            case 'filiere': $n['icon'] = '🎓'; break;
            case 'announcement': $n['icon'] = '📢'; break;
            case 'maintenance': $n['icon'] = '🛠️'; break;
            case 'orientation': $n['icon'] = '🧭'; break;
            case 'deadline': $n['icon'] = '⏰'; break;
            default: $n['icon'] = '🔔';
        }
    }

    header('Content-Type: application/json');
    echo json_encode($notifications);
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(["error" => $e->getMessage()]);
}
?>
