<?php
$pageTitle = "Saved Schools";
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

<h1 class="page-title">⭐ My Saved Schools</h1>

<?php if (isset($_GET['success'])): ?>
    <div class="msg msg-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="msg msg-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
<?php endif; ?>

<?php if (count($schools) == 0): ?>
    <div class="empty-state">
        <div class="icon">📭</div>
        <p>You haven't saved any schools yet.</p>
        <a href="institutions.php" class="btn btn-primary" style="margin-top:15px;">Browse Schools</a>
    </div>
<?php else: ?>
    <div class="cards-grid">
        <?php foreach($schools as $s): ?>
            <div class="card">
                <h3><?php echo htmlspecialchars($s["name"]); ?></h3>
                <p><span class="label">City:</span> <?php echo htmlspecialchars($s["city"]); ?></p>
                <p><span class="label">Type:</span> <span class="badge"><?php echo htmlspecialchars($s["type"]); ?></span></p>
                <p><span class="label">Min Average:</span> <?php echo htmlspecialchars($s["min_average"]); ?>/20</p>
                <p><?php echo htmlspecialchars($s["description"]); ?></p>
                <div class="requirements">
                    <span class="label">Requirements:</span> <?php echo htmlspecialchars($s["requirements"]); ?>
                </div>
                <a href="../remove_school.php?id=<?php echo $s['id']; ?>" class="btn btn-danger" onclick="return confirm('Remove this school?');">❌ Remove</a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require "../includes/footer.php"; ?>