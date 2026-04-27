<?php
session_start();
require "config/DataBase.php";

$pageTitle = "AI Results";
require "includes/header.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: views/ai_form.php");
    exit();
}

$bac_branch = trim($_POST["bac_branch"]);
$average = floatval($_POST["average"]);
$city = trim($_POST["city"]);

// --- Smart matching logic ---
// Map bac branches to institution types they can access
$branchToTypes = [
    'SVT'     => ['Engineering', 'Science', 'Technical', 'University', 'Preparatory', 'Private', 'Education'],
    'PC'      => ['Engineering', 'Science', 'Technical', 'University', 'Preparatory', 'Private', 'Education'],
    'Math'    => ['Engineering', 'Science', 'Technical', 'Business', 'University', 'Preparatory', 'Private', 'Education'],
    'Eco'     => ['Business', 'University', 'Private', 'Education'],
    'Tech'    => ['Technical', 'Science', 'University', 'Private', 'Education'],
    'Lettres' => ['University', 'Education', 'Private'],
];

$allowedTypes = isset($branchToTypes[$bac_branch]) ? $branchToTypes[$bac_branch] : ['University', 'Private'];

// Build query: filter by min_average and allowed types
$placeholders = implode(',', array_fill(0, count($allowedTypes), '?'));

$sql = "SELECT * FROM institutions 
        WHERE min_average <= ? 
        AND type IN ($placeholders)";

$params = [$average];
$params = array_merge($params, $allowedTypes);

// If city is provided, prioritize it (show city matches first)
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

// Save recommendation to ai_recommendations table
if (isset($_SESSION['user_id'])) {
    $resultText = count($results) . " schools found for $bac_branch student with $average average";
    $saveSql = "INSERT INTO ai_recommendations (student_id, result) VALUES (?, ?)";
    $saveStmt = $pdo->prepare($saveSql);
    $saveStmt->execute([$_SESSION['user_id'], $resultText]);
}
?>

<h1 class="page-title">🤖 AI Recommendations</h1>

<div class="msg msg-success">
    Found <strong><?php echo count($results); ?></strong> schools matching your profile: 
    <strong><?php echo htmlspecialchars($bac_branch); ?></strong> — 
    Average: <strong><?php echo htmlspecialchars($average); ?>/20</strong>
    <?php if (!empty($city)): ?>
        — City: <strong><?php echo htmlspecialchars($city); ?></strong>
    <?php endif; ?>
</div>

<?php if (count($results) == 0): ?>
    <div class="empty-state">
        <div class="icon">😔</div>
        <p>No schools match your criteria. Try adjusting your average or removing the city filter.</p>
        <a href="views/ai_form.php" class="btn btn-primary" style="margin-top:15px;">Try Again</a>
    </div>
<?php else: ?>
    <div class="cards-grid">
        <?php foreach($results as $r): ?>
            <div class="card">
                <h3><?php echo htmlspecialchars($r["name"]); ?></h3>
                <p><span class="label">City:</span> <?php echo htmlspecialchars($r["city"]); ?>
                    <?php if (!empty($city) && strtolower($r["city"]) === strtolower($city)): ?>
                        <span class="badge" style="background:#fff3cd; color:#856404;">📍 Your city</span>
                    <?php endif; ?>
                </p>
                <p><span class="label">Type:</span> <span class="badge"><?php echo htmlspecialchars($r["type"]); ?></span></p>
                <p><span class="label">Min Average:</span> <?php echo htmlspecialchars($r["min_average"]); ?>/20</p>
                <p><?php echo htmlspecialchars($r["description"]); ?></p>
                <div class="requirements">
                    <span class="label">Requirements:</span> <?php echo htmlspecialchars($r["requirements"]); ?>
                </div>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="save_school.php?id=<?php echo $r['id']; ?>" class="btn btn-save">⭐ Save</a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div style="text-align:center; margin-top:25px;">
    <a href="views/ai_form.php" class="btn btn-primary">🔄 Try Again</a>
</div>

<?php require "includes/footer.php"; ?>