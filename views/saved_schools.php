<?php
$pageTitle = "Sauvegardés";
require "../includes/header.php";
require "../config/DataBase.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION["user_id"];

$sql = "SELECT institutions.*
        FROM saved_schools
        JOIN institutions 
        ON saved_schools.institution_id = institutions.id
        WHERE saved_schools.student_id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$student_id]);
$schools = $stmt->fetchAll();
?>

<h1 class="page-title">Mes écoles sauvegardées</h1>

<?php if (isset($_GET['success'])): ?>
    <div class="msg msg-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="msg msg-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
<?php endif; ?>

<?php if (count($schools) == 0): ?>
    <div class="empty-state">
        <div class="icon">📭</div>
        <p>Tu n'as pas encore sauvegardé d'école.</p>
        <a href="institutions.php" class="btn btn-orange btn-lg" style="margin-top:15px;">Explorer les universités</a>
    </div>
<?php else: ?>
    <div class="cards-grid">
        <?php foreach($schools as $s): ?>
            <div class="card">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:8px;">
                    <h3><?php echo htmlspecialchars($s["name"]); ?></h3>
                    <span class="badge"><?php echo htmlspecialchars($s["type"]); ?></span>
                </div>
                <p><?php echo htmlspecialchars($s["city"]); ?></p>
                <p><?php echo htmlspecialchars($s["description"]); ?></p>
                <p><span class="label">Moyenne min:</span> <?php echo htmlspecialchars($s["min_average"]); ?>/20</p>
                <div class="requirements"><?php echo htmlspecialchars($s["requirements"]); ?></div>
                <div class="card-actions">
                    <a href="../remove_school.php?id=<?php echo $s['id']; ?>" class="btn btn-danger" onclick="return confirm('Supprimer cette école ?');">Supprimer</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require "../includes/footer.php"; ?>