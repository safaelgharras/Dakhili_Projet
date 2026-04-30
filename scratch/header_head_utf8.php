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

// Get unread notifications count
$unreadCount = 0;
if (isset($_SESSION['user_id'])) {
    try {
        $notifStmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE student_id = ? AND is_read = 0");
        $notifStmt->execute([$_SESSION['user_id']]);
        $unreadCount = $notifStmt->fetchColumn();
    } catch (Exception $e) {}
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maslaki ÔÇö <?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Orientation'; ?></title>
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
                <li><a href="<?php echo $base; ?>views/institutions.php">├ëtablissements</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="<?php echo $base; ?>views/dashboard.php">Mon Profil</a></li>
                    <li><a href="<?php echo $base; ?>views/ai_form.php" class="btn btn-accent">Orientation IA ­ƒñû</a></li>
                    <li class="notif-menu-item">
                        <div class="notif-icon-wrapper" id="notifBtn">
                            <span class="notif-bell">­ƒöö</span>
                            <?php if ($unreadCount > 0): ?>
                                <span class="notif-badge"><?php echo $unreadCount; ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="notif-dropdown" id="notifDropdown">
                            <div class="notif-dropdown-header">
                                <h3>Notifications</h3>
                                <button id="markAllRead">Tout marquer comme lu</button>
                            </div>
                            <div class="notif-dropdown-list" id="notifList">
                                <!-- Loaded via AJAX -->
                                <div class="notif-loading">Chargement...</div>
                            </div>
                        </div>
                    </li>
                    <li class="user-menu-item">
                        <div class="user-profile-icon" id="profileBtn">­ƒæñ</div>
                        <div class="profile-dropdown" id="profileDropdown">
                            <a href="<?php echo $base; ?>views/logout.php" class="dropdown-link logout-red">D├®connexion</a>
                        </div>
                    </li>
                <?php else: ?>
                    <li><a href="<?php echo $base; ?>views/login.php">Connexion</a></li>
                    <li><a href="<?php echo $base; ?>views/register.php" class="btn btn-accent">S'inscrire</a></li>
                <?php endif; ?>
            </ul>

            <div class="menu-toggle">Ôÿ░</div>
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

/* Notification Styles */
.notif-menu-item { position: relative; list-style: none; margin-left: 20px; }
.notif-icon-wrapper { position: relative; cursor: pointer; font-size: 1.4rem; transition: var(--transition); padding: 5px; }
.notif-icon-wrapper:hover { transform: scale(1.1); }
.notif-badge { position: absolute; top: -2px; right: -2px; background: #ef4444; color: #fff; font-size: 0.7rem; font-weight: 700; min-width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }

.notif-dropdown { position: absolute; top: calc(100% + 15px); right: 0; background: #fff; width: 320px; border-radius: 16px; box-shadow: var(--shadow-lg); border: 1px solid var(--border-color); display: none; z-index: 2000; overflow: hidden; }
.notif-dropdown.active { display: block; animation: fadeIn 0.2s ease-out; }

.notif-dropdown-header { padding: 15px 20px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background: #f8fafc; }
.notif-dropdown-header h3 { font-size: 1rem; font-weight: 700; color: var(--primary); }
.notif-dropdown-header button { background: none; border: none; color: var(--primary-light); font-size: 0.8rem; font-weight: 600; cursor: pointer; }
.notif-dropdown-header button:hover { text-decoration: underline; }

.notif-dropdown-list { max-height: 400px; overflow-y: auto; }
.notif-item { padding: 15px 20px; border-bottom: 1px solid #f1f5f9; transition: var(--transition); cursor: pointer; display: flex; gap: 12px; }
.notif-item:hover { background: #f8fafc; }
.notif-item.unread { background: #eff6ff; }
.notif-item.unread:hover { background: #dbeafe; }
.notif-content p { font-size: 0.85rem; color: var(--text-dark); margin-bottom: 4px; line-height: 1.4; }
.notif-time { font-size: 0.75rem; color: var(--text-muted); }
.notif-empty { padding: 40px 20px; text-align: center; color: var(--text-muted); font-size: 0.9rem; }
.notif-loading { padding: 20px; text-align: center; color: var(--text-muted); }

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

    const notifBtn = document.getElementById('notifBtn');
    const notifDropdown = document.getElementById('notifDropdown');
    const notifList = document.getElementById('notifList');

    if (notifBtn && notifDropdown) {
        notifBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            notifDropdown.classList.toggle('active');
            if (notifDropdown.classList.contains('active')) {
                loadNotifications();
            }
        });

        document.addEventListener('click', function() {
            notifDropdown.classList.remove('active');
        });

        notifDropdown.addEventListener('click', (e) => e.stopPropagation());
    }

    function loadNotifications() {
        const base = '<?php echo $base; ?>';
        fetch(base + 'get_notifications.php')
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    notifList.innerHTML = '<div class="notif-empty">Aucune notification pour le moment.</div>';
                    return;
                }

                notifList.innerHTML = data.map(n => `
                    <div class="notif-item ${n.is_read == 0 ? 'unread' : ''}" onclick="markAsRead(${n.id}, this)">
                        <div class="notif-content">
                            <p>${n.message}</p>
                            <span class="notif-time">${n.time_ago}</span>
                        </div>
                    </div>
                `).join('');
            });
    }

    window.markAsRead = function(id, element) {
        const base = '<?php echo $base; ?>';
        fetch(base + 'mark_notification_read.php?id=' + id)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    element.classList.remove('unread');
                    updateBadgeCount();
                }
            });
    }

    function updateBadgeCount() {
        const badge = document.querySelector('.notif-badge');
        if (badge) {
            let count = parseInt(badge.textContent);
            if (count > 1) {
                badge.textContent = count - 1;
            } else {
                badge.remove();
            }
        }
    }

    const markAllReadBtn = document.getElementById('markAllRead');
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', () => {
            const base = '<?php echo $base; ?>';
            fetch(base + 'mark_notification_read.php?all=1')
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.querySelectorAll('.notif-item').forEach(i => i.classList.remove('unread'));
                        const badge = document.querySelector('.notif-badge');
                        if (badge) badge.remove();
                    }
                });
        });
    }
});
</script>



<main class="main-content">
