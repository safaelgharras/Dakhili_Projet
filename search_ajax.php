<?php
require "config/DataBase.php";

$search = isset($_GET["search"]) ? trim($_GET["search"]) : "";
$cityId = isset($_GET["city_id"]) ? trim($_GET["city_id"]) : "";
$catId = isset($_GET["cat_id"]) ? trim($_GET["cat_id"]) : "";
$type = isset($_GET["type"]) ? trim($_GET["type"]) : "";

$sql = "SELECT DISTINCT i.* FROM institutions i 
        LEFT JOIN institution_filieres ifil ON i.id = ifil.institution_id
        LEFT JOIN filieres f ON ifil.filiere_id = f.id
        WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND (i.name LIKE ? OR i.description LIKE ? OR f.nom LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($cityId)) {
    $sql .= " AND i.ville_id = ?";
    $params[] = $cityId;
}

if (!empty($catId)) {
    $sql .= " AND f.categorie_id = ?";
    $params[] = $catId;
}

if (!empty($type)) {
    $sql .= " AND i.type = ?";
    $params[] = $type;
}

$sql .= " ORDER BY i.is_popular DESC, i.name ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$institutions = $stmt->fetchAll(PDO::FETCH_ASSOC);

header("Content-Type: application/json");
echo json_encode($institutions);
?>
