<?php
require "config/DataBase.php";

$search = isset($_GET["search"]) ? trim($_GET["search"]) : "";
$city = isset($_GET["city"]) ? trim($_GET["city"]) : "";
$type = isset($_GET["type"]) ? trim($_GET["type"]) : "";

$sql = "SELECT * FROM institutions WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND (name LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($city)) {
    $sql .= " AND city = ?";
    $params[] = $city;
}

if (!empty($type)) {
    $sql .= " AND type = ?";
    $params[] = $type;
}

$sql .= " ORDER BY name ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$institutions = $stmt->fetchAll(PDO::FETCH_ASSOC);

header("Content-Type: application/json");
echo json_encode($institutions);
?>
