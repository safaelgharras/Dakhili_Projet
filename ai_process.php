<?php
session_start();
require "config/DataBase.php";

$pageTitle = "Résultats IA";
require "includes/header.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: views/ai_form.php");
    exit();
}

$bac_branch = trim($_POST["bac_branch"]);
$average = floatval($_POST["average"]);
$city = trim($_POST["city"]);

// Map bac branches to institution types
$branchToTypes = [
    'SVT'     => ['Engineering', 'Science', 'Technical', 'University', 'Preparatory', 'Private', 'Education'],
    'PC'      => ['Engineering', 'Science', 'Technical', 'University', 'Preparatory', 'Private', 'Education'],
    'Math'    => ['Engineering', 'Science', 'Technical', 'Business', 'University', 'Preparatory', 'Private', 'Education'],
    'Eco'     => ['Business', 'University', 'Private', 'Education'],
    'Tech'    => ['Technical', 'Science', 'University', 'Private', 'Education'],
    'Lettres' => ['University', 'Education', 'Private'],
];

$allowedTypes = isset($branchToTypes[$bac_branch]) ? $branchToTypes[$bac_branch] : ['University', 'Private'];

$placeholders = implode(',', array_fill(0, count($allowedTypes), '?'));

$sql = "SELECT * FROM institutions 
        WHERE min_average <= ? 
        AND type IN ($placeholders)";

$params = [$average];
$params = array_merge($params, $allowedTypes);

if (!empty($city)) {
    $sql .= " ORDER BY 
                CASE WHEN city = ? THEN 0 ELSE 1 END,
                min_average DESC";
    $params[] = $city;
} else {
    $sql .= " ORDER BY min_average DESC";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll();

// Save recommendation
if (isset($_SESSION['user_id'])) {
    $resultText = count($results) . " écoles trouvées pour $bac_branch avec $average de moyenne";
    $saveSql = "INSERT INTO ai_recommendations (student_id, result) VALUES (?, ?)";
    $saveStmt = $pdo->prepare($saveSql);
    $saveStmt->execute([$_SESSION['user_id'], $resultText]);
}
?>

<h1 class="page-title">Recommandations IA</h1>

<div class="msg msg-success">
    <strong><?php echo count($results); ?></strong> école(s) correspondent à ton profil :
    <strong><?php echo htmlspecialchars($bac_branch); ?></strong> — 
    Moyenne: <strong><?php echo htmlspecialchars($average); ?>/20</strong>
    <?php if (!empty($city)): ?>
        — Ville: <strong><?php echo htmlspecialchars($city); ?></strong>
    <?php endif; ?>
</div>

<?php if (count($results) == 0): ?>
    <div class="empty-state">
        <div class="icon">😔</div>
        <p>Aucune école ne correspond à tes critères. Essaie d'ajuster ta moyenne ou ta ville.</p>
        <a href="views/ai_form.php" class="btn btn-orange btn-lg" style="margin-top:15px;">Réessayer</a>
    </div>
<?php else: ?>
    <div class="cards-grid">
        <?php foreach($results as $r): ?>
            <div class="card">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:8px;">
                    <h3><?php echo htmlspecialchars($r["name"]); ?></h3>
                    <span class="badge"><?php echo htmlspecialchars($r["type"]); ?></span>
                </div>
                <p><?php echo htmlspecialchars($r["city"]); ?>
                    <?php if (!empty($city) && strtolower($r["city"]) === strtolower($city)): ?>
                        <span class="badge" style="background:#fff3cd; color:#856404;">📍 Ta ville</span>
                    <?php endif; ?>
                </p>
                <p><?php echo htmlspecialchars($r["description"]); ?></p>
                <p><span class="label">Moyenne min:</span> <?php echo htmlspecialchars($r["min_average"]); ?>/20</p>
                <div class="requirements"><?php echo htmlspecialchars($r["requirements"]); ?></div>
                <div class="card-actions">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="save_school.php?id=<?php echo $r['id']; ?>" class="btn btn-save">Sauvegarder</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div style="text-align:center; margin-top:25px;">
    <a href="views/ai_form.php" class="btn btn-primary btn-lg">Réessayer</a>
</div>

<?php require "includes/footer.php"; ?>