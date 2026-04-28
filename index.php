<?php
require "config/DataBase.php";

// Get real stats with safety
$schoolCount = 0; $cityCount = 0; $typeCount = 0;
try {
    $schoolCount = $pdo->query("SELECT COUNT(*) FROM institutions")->fetchColumn();
    $cityCount = $pdo->query("SELECT COUNT(DISTINCT ville_id) FROM institutions")->fetchColumn();
    if ($cityCount == 0) $cityCount = $pdo->query("SELECT COUNT(DISTINCT city) FROM institutions")->fetchColumn();
    $typeCount = $pdo->query("SELECT COUNT(DISTINCT type) FROM institutions")->fetchColumn();
} catch (Exception $e) {}


$pageTitle = "Accueil";
require "includes/header.php";
?>

<div class="hero">
    <div class="hero-content">
        <div class="hero-badge">🎓 Plateforme n°1 d'orientation au Maroc</div>
        <h1 class="text-gradient">Trouvez votre avenir <span class="text-accent">universitaire</span></h1>
        <p>Explorez les meilleures institutions, découvrez les filières d'excellence et construisez votre parcours professionnel avec l'aide de notre intelligence artificielle.</p>
        <div class="hero-buttons">
            <a href="views/institutions.php" class="btn btn-hero btn-hero-primary">Découvrir les établissements</a>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="views/register.php" class="btn btn-hero btn-hero-secondary">Créer mon compte</a>
            <?php else: ?>
                <a href="views/dashboard.php" class="btn btn-hero btn-hero-secondary">Aller au Dashboard</a>
            <?php endif; ?>
        </div>
    </div>
</div>


<div class="stats-section">
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon">🏫</div>
            <div class="stat-info">
                <div class="stat-number"><?php echo $schoolCount; ?>+</div>
                <div class="stat-label">Établissements</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📍</div>
            <div class="stat-info">
                <div class="stat-number"><?php echo $cityCount; ?></div>
                <div class="stat-label">Villes couvertes</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📚</div>
            <div class="stat-info">
                <div class="stat-number"><?php echo $typeCount; ?></div>
                <div class="stat-label">Types de formations</div>
            </div>
        </div>
    </div>
</div>

<section class="popular-section">
    <h2 class="section-title">Établissements populaires</h2>
    <div class="cards-grid">
        <?php
        // Check if is_popular column exists to avoid fatal error
        $columnExists = false;
        try {
            $pdo->query("SELECT is_popular FROM institutions LIMIT 1");
            $columnExists = true;
        } catch (Exception $e) {
            $columnExists = false;
        }

        if ($columnExists) {
            $popular = $pdo->query("SELECT * FROM institutions WHERE is_popular = 1 LIMIT 3")->fetchAll();
        } else {
            // Fallback if migration hasn't run
            $popular = $pdo->query("SELECT * FROM institutions LIMIT 3")->fetchAll();
        }

        if (empty($popular)): ?>
            <p style="text-align:center; color:var(--text-muted); width:100%;">Aucun établissement disponible pour le moment.</p>
        <?php else:
            foreach ($popular as $school):
        ?>
        <div class="card">
            <img src="assets/images/institutions/<?php echo $school['image'] ?? 'default_school.jpg'; ?>" class="card-img" alt="<?php echo $school['name']; ?>">
            <div class="card-body">
                <div class="card-tag"><?php echo $school['type']; ?></div>
                <h3><?php echo $school['name']; ?></h3>
                <p class="school-location">📍 <?php echo $school['city'] ?? 'Maroc'; ?></p>
                <div class="card-footer">
                    <span class="seuil">Seuil: <strong><?php echo $school['seuil'] ?? '--'; ?></strong></span>
                    <a href="views/institution_detail.php?id=<?php echo $school['id']; ?>" class="btn-link">Voir détails →</a>
                </div>
            </div>
        </div>
        <?php endforeach; 
        endif; ?>
    </div>
</section>



<?php require "includes/footer.php"; ?>