<?php
$pageTitle = "Dashboard";
require "../includes/header.php";
require "../config/DataBase.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Get saved schools count
$savedCount = $pdo->prepare("SELECT COUNT(*) FROM saved_schools WHERE student_id = ?");
$savedCount->execute([$_SESSION['user_id']]);
$savedNum = $savedCount->fetchColumn();

// Get total institutions
$totalSchools = $pdo->query("SELECT COUNT(*) FROM institutions")->fetchColumn();

// Get upcoming deadlines for saved schools
$deadlineSql = "SELECT institutions.name, institutions.city, deadlines.deadline_date
                FROM saved_schools
                JOIN institutions ON saved_schools.institution_id = institutions.id
                JOIN deadlines ON institutions.id = deadlines.institution_id
                WHERE saved_schools.student_id = ?
                AND deadlines.deadline_date >= CURDATE()
                ORDER BY deadlines.deadline_date ASC
                LIMIT 5";
$deadlineStmt = $pdo->prepare($deadlineSql);
$deadlineStmt->execute([$_SESSION['user_id']]);
$upcomingDeadlines = $deadlineStmt->fetchAll();
?>

<div class="dashboard-welcome">
    <h2>Bienvenue, <?php echo htmlspecialchars($_SESSION["user_name"]); ?> 👋</h2>
    <p>Explore les établissements, obtiens des recommandations personnalisées et planifie ton avenir.</p>
</div>

<div class="dashboard-links">
    <a href="institutions.php" class="dash-card">
        <div class="icon">🏫</div>
        <h3>Universités</h3>
        <p><?php echo $totalSchools; ?> établissements disponibles</p>
    </a>

    <a href="saved_schools.php" class="dash-card">
        <div class="icon">⭐</div>
        <h3>Sauvegardés</h3>
        <p><?php echo $savedNum; ?> école(s) sauvegardée(s)</p>
    </a>

    <a href="ai_form.php" class="dash-card">
        <div class="icon">🤖</div>
        <h3>Orientation IA</h3>
        <p>Recommandations personnalisées</p>
    </a>
</div>

<?php if (count($upcomingDeadlines) > 0): ?>
<div class="deadline-list">
    <h2>📅 Dates limites à venir</h2>
    <?php foreach($upcomingDeadlines as $d): ?>
        <?php
            $deadline = new DateTime($d["deadline_date"]);
            $today = new DateTime();
            $diff = $today->diff($deadline)->days;
            
            if ($diff <= 7) {
                $deadlineClass = "deadline-urgent";
            } elseif ($diff <= 30) {
                $deadlineClass = "deadline-soon";
            } else {
                $deadlineClass = "deadline-normal";
            }
        ?>
        <div class="deadline-item">
            <div>
                <div class="school-name"><?php echo htmlspecialchars($d["name"]); ?></div>
                <div class="school-city"><?php echo htmlspecialchars($d["city"]); ?></div>
            </div>
            <div class="deadline-badge <?php echo $deadlineClass; ?>">
                📅 <?php echo $deadline->format("d/m/Y"); ?>
                <?php if ($diff <= 7): ?> — <?php echo $diff; ?> jour(s) !<?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php require "../includes/footer.php"; ?>