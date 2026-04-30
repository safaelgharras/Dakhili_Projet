<?php
$pageTitle = "Mon Espace";
require "../includes/header.php";
require "../config/DataBase.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION["user_id"];

// Get saved schools count
$savedCount = $pdo->prepare("SELECT COUNT(*) FROM saved_schools WHERE student_id = ?");
$savedCount->execute([$userId]);
$savedNum = $savedCount->fetchColumn();

// Get total institutions
$totalSchools = $pdo->query("SELECT COUNT(*) FROM institutions")->fetchColumn();

// Get upcoming deadlines for saved schools
$upcomingDeadlines = [];
try {
    $deadlineSql = "SELECT i.name, i.city, d.deadline_date
                    FROM saved_schools s
                    JOIN institutions i ON s.institution_id = i.id
                    JOIN deadlines d ON i.id = d.institution_id
                    WHERE s.student_id = ?
                    AND d.deadline_date >= CURDATE()
                    ORDER BY d.deadline_date ASC
                    LIMIT 5";
    $deadlineStmt = $pdo->prepare($deadlineSql);
    $deadlineStmt->execute([$userId]);
    $upcomingDeadlines = $deadlineStmt->fetchAll();
} catch (Exception $e) {}
?>

<div class="dashboard-container">
    <header class="dashboard-banner">
        <div class="banner-content">
            <span class="welcome-tag">­ƒæï Bienvenue sur votre espace</span>
            <h1>Bonjour, <?php echo htmlspecialchars($_SESSION["user_name"]); ?></h1>
            <p>Pr├¬t ├á construire votre avenir ? Voici un aper├ºu de votre progression et des opportunit├®s recommand├®es pour vous.</p>
        </div>
        <div class="banner-stats">
            <div class="b-stat">
                <strong><?php echo $savedNum; ?></strong>
                <span>├ëcoles suivies</span>
            </div>
            <div class="b-stat">
                <strong>92%</strong>
                <span>Profil compl├®t├®</span>
            </div>
        </div>
    </header>

    <div class="dashboard-grid">
        <section class="dash-main">
            <div class="section-header">
                <h2>Raccourcis rapides</h2>
            </div>
            <div class="quick-links">
                <a href="institutions.php" class="quick-card">
                    <div class="q-icon">­ƒÅ½</div>
                    <div class="q-info">
                        <h3>├ëtablissements</h3>
                        <p>Explorez <?php echo $totalSchools; ?> ├®coles</p>
                    </div>
                    <span class="q-arrow">ÔåÆ</span>
                </a>

                <a href="saved_schools.php" class="quick-card">
                    <div class="q-icon">Ô¡É</div>
                    <div class="q-info">
                        <h3>Ma Liste</h3>
                        <p><?php echo $savedNum; ?> ├®coles enregistr├®es</p>
                    </div>
                    <span class="q-arrow">ÔåÆ</span>
                </a>

                <a href="ai_form.php" class="quick-card q-primary">
                    <div class="q-icon">­ƒñû</div>
                    <div class="q-info">
                        <h3>Conseiller IA</h3>
                        <p>Orientation personnalis├®e</p>
                    </div>
                    <span class="q-arrow">ÔåÆ</span>
                </a>

                <a href="contests.php" class="quick-card">
                    <div class="q-icon">­ƒÄô</div>
                    <div class="q-info">
                        <h3>Concours</h3>
                        <p>Calendrier & Inscriptions</p>
                    </div>
                    <span class="q-arrow">ÔåÆ</span>
                </a>
            </div>
            
            <div class="contests-section" style="margin-bottom: 40px;">
                <div class="section-header">
                    <h2>­ƒöÑ Concours Importants</h2>
                </div>
                <div class="contest-grid">
                    <?php
                    try {
                        $contestStmt = $pdo->query("SELECT c.*, i.name as institution_name FROM contests c JOIN institutions i ON c.institution_id = i.id WHERE c.is_featured = 1 LIMIT 3");
                        $featuredContests = $contestStmt->fetchAll();
                        
                        foreach($featuredContests as $c): 
                            $statusLabel = $c['status'] == 'open' ? 'Ouvert' : ($c['status'] == 'soon' ? 'Bient├┤t' : 'Ferm├®');
                        ?>
                            <div class="contest-card">
                                <span class="contest-status status-<?php echo $c['status']; ?>"><?php echo $statusLabel; ?></span>
                                <h4><?php echo htmlspecialchars($c['title']); ?></h4>
                                <div class="contest-details">
                                    <span class="contest-info">­ƒÅ½ <?php echo htmlspecialchars($c['institution_name']); ?></span>
                                    <span class="contest-info">­ƒôà Exam: <?php echo date('d M Y', strtotime($c['exam_date'])); ?></span>
                                    <span class="contest-info">ÔÅ│ Limite: <?php echo date('d M Y', strtotime($c['registration_deadline'])); ?></span>
                                </div>
                                <div class="contest-footer">
                                    <a href="institution_detail.php?id=<?php echo $c['institution_id']; ?>" class="btn-details">Voir d├®tails ÔåÆ</a>
                                </div>
                            </div>
                        <?php endforeach;
                    } catch (Exception $e) {}
                    ?>
                </div>
            </div>

            <?php if (count($upcomingDeadlines) > 0): ?>
            <div class="deadline-section">
                <div class="section-header">
                    <h2>­ƒôà Dates limites cruciales</h2>
                </div>
                <div class="deadline-grid">
                    <?php foreach($upcomingDeadlines as $d): ?>
                        <div class="deadline-card">
                            <div class="d-date">
                                <span class="d-day"><?php echo date('d', strtotime($d['deadline_date'])); ?></span>
                                <span class="d-month"><?php echo date('M', strtotime($d['deadline_date'])); ?></span>
                            </div>
                            <div class="d-info">
                                <h4><?php echo htmlspecialchars($d['name']); ?></h4>
                                <p>­ƒôì <?php echo htmlspecialchars($d['city']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </section>

        <aside class="dash-sidebar">
            <div class="sidebar-card promo-card">
                <h3>Voulez-vous plus d'aide ?</h3>
                <p>Nos experts en orientation sont l├á pour vous accompagner dans chaque ├®tape de votre inscription.</p>
                <a href="appointments.php" class="btn btn-white btn-full">Prendre RDV</a>
            </div>

            <div class="sidebar-card">
                <h3>Notifications</h3>
                <div class="notif-list">
                    <?php
                    try {
                        $dashNotifs = $pdo->prepare("SELECT * FROM notifications WHERE student_id = ? ORDER BY created_at DESC LIMIT 5");
                        $dashNotifs->execute([$userId]);
                        $notifs = $dashNotifs->fetchAll();
                        
                        if (count($notifs) > 0):
                            foreach($notifs as $n): ?>
                                <div class="notif-item <?php echo $n['is_read'] ? '' : 'unread-dot'; ?>">
                                    <span class="n-dot"></span>
                                    <p><?php echo htmlspecialchars($n['message']); ?></p>
                                </div>
                            <?php endforeach;
                        else: ?>
                            <p class="text-muted">Aucune notification.</p>
                        <?php endif;
                    } catch (Exception $e) {
                        echo "<p>Erreur de chargement.</p>";
                    }
                    ?>
                </div>
            </div>
        </aside>
    </div>
</div>

<style>
.dashboard-container { max-width: 1200px; margin: 0 auto; padding: 20px; }
.dashboard-banner { 
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    border-radius: var(--radius-lg);
    padding: 60px 40px;
    color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 40px;
    box-shadow: var(--shadow-lg);
}
.welcome-tag { display: inline-block; background: rgba(255,255,255,0.1); padding: 5px 15px; border-radius: 20px; font-size: 0.8rem; margin-bottom: 15px; }
.dashboard-banner h1 { font-size: 2.5rem; margin-bottom: 10px; font-weight: 800; }
.dashboard-banner p { color: rgba(255,255,255,0.8); max-width: 500px; line-height: 1.6; }

.banner-stats { display: flex; gap: 30px; text-align: center; }
.b-stat strong { display: block; font-size: 2rem; color: var(--accent); }
.b-stat span { font-size: 0.8rem; color: rgba(255,255,255,0.6); }

.dashboard-grid { display: grid; grid-template-columns: 1fr 320px; gap: 40px; }
.section-header { margin-bottom: 25px; }
.section-header h2 { font-size: 1.4rem; color: var(--primary); font-weight: 800; }

.quick-links { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px; }
.quick-card { 
    background: var(--white);
    padding: 24px;
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-md);
    display: flex;
    align-items: center;
    gap: 20px;
    transition: var(--transition);
    text-decoration: none;
    color: var(--text-dark);
}
.quick-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-lg); border-color: var(--accent); }
.q-icon { font-size: 2rem; }
.q-info h3 { font-size: 1.1rem; margin-bottom: 5px; color: var(--primary); }
.q-info p { font-size: 0.85rem; color: var(--text-muted); }
.q-arrow { margin-left: auto; color: var(--border-color); font-size: 1.2rem; }
.q-primary { border-left: 4px solid var(--accent); }

.deadline-grid { display: grid; gap: 15px; }
.deadline-card { 
    background: var(--white);
    padding: 15px 20px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
}
.d-date { background: #f8fafc; padding: 10px; border-radius: 12px; text-align: center; min-width: 60px; }
.d-day { display: block; font-size: 1.2rem; font-weight: 800; color: var(--primary); }
.d-month { font-size: 0.7rem; text-transform: uppercase; color: var(--text-muted); }
.d-info h4 { font-size: 1rem; color: var(--text-dark); }
.d-info p { font-size: 0.8rem; color: var(--text-muted); }

.sidebar-card { background: var(--white); padding: 25px; border-radius: var(--radius-md); box-shadow: var(--shadow-md); margin-bottom: 20px; }
.promo-card { background: var(--primary); color: #fff; }
.promo-card h3 { color: #fff; margin-bottom: 15px; }
.promo-card p { color: rgba(255,255,255,0.8); font-size: 0.9rem; margin-bottom: 20px; }
.btn-white { background: #fff; color: var(--primary); font-weight: 700; }
.btn-full { width: 100%; }

.notif-list { display: grid; gap: 15px; }
.notif-item { display: flex; align-items: flex-start; gap: 12px; font-size: 0.85rem; color: var(--text-dark); }
.n-dot { width: 8px; height: 8px; background: var(--accent); border-radius: 50%; margin-top: 5px; flex-shrink: 0; }

@media (max-width: 992px) {
    .dashboard-grid { grid-template-columns: 1fr; }
    .dashboard-banner { flex-direction: column; text-align: center; gap: 30px; }
    .dashboard-banner p { margin: 0 auto 20px; }
}
</style>

<?php require "../includes/footer.php"; ?>
