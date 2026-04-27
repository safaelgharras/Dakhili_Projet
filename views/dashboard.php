<?php
$pageTitle = "Dashboard";
require "../includes/header.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
?>

<div class="dashboard-welcome">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION["user_name"]); ?> 👋</h2>
    <p>This is your Dakhili dashboard. Explore schools, get AI recommendations, and plan your future.</p>
</div>

<div class="dashboard-links">
    <a href="institutions.php" class="dash-card">
        <div class="icon">🏫</div>
        <h3>Browse Schools</h3>
        <p>Explore all available institutions</p>
    </a>

    <a href="saved_schools.php" class="dash-card">
        <div class="icon">⭐</div>
        <h3>Saved Schools</h3>
        <p>View your saved institutions</p>
    </a>

    <a href="ai_form.php" class="dash-card">
        <div class="icon">🤖</div>
        <h3>AI Orientation</h3>
        <p>Get personalized recommendations</p>
    </a>
</div>

<?php require "../includes/footer.php"; ?>