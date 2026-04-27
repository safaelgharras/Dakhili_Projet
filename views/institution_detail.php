<?php
$pageTitle = "Détails";
require "../includes/header.php";
require "../config/DataBase.php";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: institutions.php");
    exit();
}

$id = (int) $_GET["id"];
$isLoggedIn = isset($_SESSION['user_id']);

// Get institution details with deadline
$sql = "SELECT institutions.*, deadlines.deadline_date 
        FROM institutions 
        LEFT JOIN deadlines ON institutions.id = deadlines.institution_id
        WHERE institutions.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$inst = $stmt->fetch();

if (!$inst) {
    header("Location: institutions.php?error=École introuvable");
    exit();
}

// Get approved reviews
$reviewSql = "SELECT reviews.*, students.name AS author_name 
              FROM reviews 
              JOIN students ON reviews.student_id = students.id
              WHERE reviews.institution_id = ? AND reviews.status = 'approved'
              ORDER BY reviews.created_at DESC";
$reviewStmt = $pdo->prepare($reviewSql);
$reviewStmt->execute([$id]);
$reviews = $reviewStmt->fetchAll();

// Check if user already reviewed
$hasReviewed = false;
if ($isLoggedIn) {
    $checkReview = $pdo->prepare("SELECT id FROM reviews WHERE student_id = ? AND institution_id = ?");
    $checkReview->execute([$_SESSION['user_id'], $id]);
    $hasReviewed = $checkReview->fetch() ? true : false;
}
?>

<div style="margin-bottom:15px;">
    <a href="institutions.php" style="color:var(--orange); font-size:0.85rem;">← Retour aux universités</a>
</div>

<div class="card" style="border-left:4px solid var(--orange); margin-bottom:20px;">
    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:8px;">
        <h3 style="font-size:1.2rem;"><?php echo htmlspecialchars($inst["name"]); ?></h3>
        <span class="badge"><?php echo htmlspecialchars($inst["type"]); ?></span>
    </div>
    <p><span class="label">Ville:</span> <?php echo htmlspecialchars($inst["city"]); ?></p>
    <p><?php echo htmlspecialchars($inst["description"]); ?></p>
    <p><span class="label">Moyenne min:</span> <?php echo htmlspecialchars($inst["min_average"]); ?>/20</p>
    <div class="requirements"><?php echo htmlspecialchars($inst["requirements"]); ?></div>

    <?php if (!empty($inst["deadline_date"])): ?>
        <?php
            $deadline = new DateTime($inst["deadline_date"]);
            $today = new DateTime();
            $diff = $today->diff($deadline)->days;
            $isPast = $deadline < $today;
            
            if ($isPast) { $cls = "deadline-past"; $txt = "Expiré"; }
            elseif ($diff <= 7) { $cls = "deadline-urgent"; $txt = "Dans $diff jour(s)"; }
            elseif ($diff <= 30) { $cls = "deadline-soon"; $txt = $deadline->format("d/m/Y"); }
            else { $cls = "deadline-normal"; $txt = $deadline->format("d/m/Y"); }
        ?>
        <div class="deadline-badge <?php echo $cls; ?>" style="margin-top:12px;">
            📅 Date limite: <?php echo $txt; ?>
        </div>
    <?php endif; ?>

    <div class="card-actions">
        <?php if ($isLoggedIn): ?>
            <a href="../save_school.php?id=<?php echo $inst['id']; ?>" class="btn btn-save">Sauvegarder</a>
        <?php endif; ?>
    </div>
</div>

<!-- Reviews Section -->
<h2 style="font-size:1.2rem; color:var(--navy); margin-bottom:15px; font-weight:700;">
    💬 Avis (<?php echo count($reviews); ?>)
</h2>

<?php if (isset($_GET['review_success'])): ?>
    <div class="msg msg-success">Ton avis a été soumis et sera publié après validation.</div>
<?php endif; ?>

<?php if (isset($_GET['review_error'])): ?>
    <div class="msg msg-error"><?php echo htmlspecialchars($_GET['review_error']); ?></div>
<?php endif; ?>

<?php if ($isLoggedIn && !$hasReviewed): ?>
    <div class="review-form">
        <form method="POST" action="../submit_review.php">
            <input type="hidden" name="institution_id" value="<?php echo $inst['id']; ?>">
            <div class="form-group" style="margin-bottom:10px;">
                <textarea name="content" placeholder="Partage ton expérience avec cette école..." required></textarea>
            </div>
            <button type="submit" class="btn btn-orange">Envoyer l'avis</button>
        </form>
    </div>
<?php elseif ($isLoggedIn && $hasReviewed): ?>
    <div class="msg" style="background:var(--orange-light); color:var(--orange-dark); border:1px solid var(--orange);">
        Tu as déjà laissé un avis pour cette école.
    </div>
<?php elseif (!$isLoggedIn): ?>
    <p style="color:var(--text-muted); font-size:0.85rem;">
        <a href="login.php" style="color:var(--orange); font-weight:600;">Connecte-toi</a> pour laisser un avis.
    </p>
<?php endif; ?>

<?php if (count($reviews) == 0): ?>
    <div class="empty-state" style="padding:30px;">
        <p>Aucun avis pour le moment. Sois le premier !</p>
    </div>
<?php else: ?>
    <?php foreach($reviews as $rev): ?>
        <div class="review-item">
            <div class="review-header">
                <span class="review-author">👤 <?php echo htmlspecialchars($rev["author_name"]); ?></span>
                <span class="review-date"><?php echo date("d/m/Y", strtotime($rev["created_at"])); ?></span>
            </div>
            <div class="review-content"><?php echo htmlspecialchars($rev["content"]); ?></div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php require "../includes/footer.php"; ?>
