<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Auto-migration check (100% Work Guarantee)
require_once __DIR__ . "/../config/DataBase.php";
try {
    $pdo->query("SELECT 1 FROM villes LIMIT 1");
} catch (Exception $e) {
    // Table missing, run migration automatically
    $sqlFile = __DIR__ . "/../database/schema_update.sql";
    if (file_exists($sqlFile)) {
        $sql = file_get_contents($sqlFile);
        $pdo->exec($sql);
    }
}

// Detect base path (works from root or views folder)
$isInViews = strpos($_SERVER['PHP_SELF'], '/views/') !== false;
$base = $isInViews ? '../' : '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maslaki — <?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Orientation'; ?></title>
    <link rel="stylesheet" href="<?php echo $base; ?>assets/css/style.css">
</head>
<body>

    <nav class="navbar">
        <div class="nav-container">
            <a href="<?php echo $base; ?>index.php" class="brand">
                <span class="logo-box">M</span>
                <span class="logo-text">Maslaki</span>
            </a>
            
            <ul class="nav-links">
                <li><a href="<?php echo $base; ?>index.php">Accueil</a></li>
                <li><a href="<?php echo $base; ?>views/institutions.php">Établissements</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="<?php echo $base; ?>views/dashboard.php">Mon Profil</a></li>
                    <li><a href="<?php echo $base; ?>views/ai_form.php" class="btn btn-accent">Orientation IA 🤖</a></li>
                    <li class="user-menu-item">
                        <div class="user-profile-icon" id="profileBtn">👤</div>
                        <div class="profile-dropdown" id="profileDropdown">
                            <a href="<?php echo $base; ?>logout.php" class="dropdown-link logout-red">Déconnexion</a>
                        </div>
                    </li>
                <?php else: ?>
                    <li><a href="<?php echo $base; ?>views/login.php">Connexion</a></li>
                    <li><a href="<?php echo $base; ?>views/register.php" class="btn btn-accent">S'inscrire</a></li>
                <?php endif; ?>
            </ul>

            <div class="menu-toggle">☰</div>
        </div>
    </nav>
</header>

<style>
.navbar { height: 85px; transition: var(--transition); }
.nav-container { height: 100%; display: flex; align-items: center; justify-content: space-between; }
.brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
.logo-box { background: var(--accent); color: #fff; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 10px; font-weight: 900; font-size: 1.4rem; box-shadow: 0 4px 10px rgba(255, 109, 0, 0.3); }
.logo-text { font-size: 1.6rem; font-weight: 800; color: var(--primary-dark); letter-spacing: -1px; }

.nav-links { display: flex; align-items: center; gap: 35px; list-style: none; margin-left: auto; margin-right: 20px; }
.nav-links a { text-decoration: none; color: var(--text-dark); font-weight: 600; font-size: 0.95rem; transition: var(--transition); }
.nav-links a:hover { color: var(--accent); }

.btn-accent { padding: 10px 20px !important; border-radius: 12px !important; }

/* Profile Dropdown Styles */
.user-menu-item { position: relative; list-style: none; margin-left: 10px; }
.user-profile-icon { width: 40px; height: 40px; background: #f8fafc; border: 1.5px solid var(--border-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: var(--transition); font-size: 1.2rem; }
.user-profile-icon:hover { border-color: var(--primary); background: #f1f5f9; }

.profile-dropdown { position: absolute; top: calc(100% + 15px); right: 0; background: #fff; min-width: 180px; border-radius: 12px; box-shadow: var(--shadow-lg); padding: 8px; display: none; z-index: 2000; border: 1px solid var(--border-color); }
.profile-dropdown.active { display: block; animation: fadeIn 0.2s ease-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

.dropdown-link { display: block; padding: 10px 16px; border-radius: 8px; text-decoration: none; color: var(--text-dark); font-weight: 600; font-size: 0.9rem; transition: var(--transition); }
.dropdown-link:hover { background: var(--bg-light); color: var(--primary); }
.dropdown-link.logout-red { color: #e11d48; }
.dropdown-link.logout-red:hover { background: #fff1f2; }

@media (max-width: 992px) {
    .nav-links { display: none; }
    .menu-toggle { display: block; font-size: 1.5rem; cursor: pointer; color: var(--primary); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const profileBtn = document.getElementById('profileBtn');
    const profileDropdown = document.getElementById('profileDropdown');

    if (profileBtn && profileDropdown) {
        profileBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            profileDropdown.classList.toggle('active');
        });

        document.addEventListener('click', function() {
            profileDropdown.classList.remove('active');
        });
    }
});
</script>



<main class="main-content">
