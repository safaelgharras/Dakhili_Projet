<?php
require "config/DataBase.php";

// Get real stats
$schoolCount = $pdo->query("SELECT COUNT(*) FROM institutions")->fetchColumn();
$cityCount = $pdo->query("SELECT COUNT(DISTINCT city) FROM institutions")->fetchColumn();
$typeCount = $pdo->query("SELECT COUNT(DISTINCT type) FROM institutions")->fetchColumn();

$pageTitle = "Accueil";
require "includes/header.php";
?>

<div class="hero">
    <div class="hero-badge">Plateforme d'orientation universitaire</div>
    <h1>Trouve ta voie au Maroc</h1>
    <p>Découvre les filières, universités et débouchés adaptés à ton baccalauréat.</p>
    <div class="hero-buttons">
        <a href="views/institutions.php" class="btn-hero btn-hero-primary">Explorer les filières</a>
        <a href="views/register.php" class="btn-hero btn-hero-secondary">S'inscrire</a>
    </div>
</div>

<div class="stats-bar">
    <div class="stat-item">
        <div class="stat-number"><?php echo $schoolCount; ?>+</div>
        <div class="stat-label">Filières</div>
    </div>
    <div class="stat-item">
        <div class="stat-number"><?php echo $cityCount; ?></div>
        <div class="stat-label">Villes</div>
    </div>
    <div class="stat-item">
        <div class="stat-number"><?php echo $typeCount; ?></div>
        <div class="stat-label">Types</div>
    </div>
    <div class="stat-item">
        <div class="stat-number">98%</div>
        <div class="stat-label">Satisfaction</div>
    </div>
</div>

<?php require "includes/footer.php"; ?>