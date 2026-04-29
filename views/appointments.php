<?php
$pageTitle = "Mes Rendez-vous";
require "../includes/header.php";
require "../config/DataBase.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION["user_id"];

// Get user appointments
$stmt = $pdo->prepare("SELECT * FROM appointments WHERE student_id = ? ORDER BY appointment_date ASC, appointment_time ASC");
$stmt->execute([$userId]);
$appointments = $stmt->fetchAll();
?>

<div class="appointments-container">
    <div class="page-header">
        <h1>🗓️ Mes Rendez-vous</h1>
        <p>Gérez vos sessions d'orientation avec nos experts.</p>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="msg msg-success">✅ Rendez-vous ajouté avec succès !</div>
    <?php endif; ?>
    <?php if (isset($_GET['deleted'])): ?>
        <div class="msg msg-success">🗑️ Rendez-vous supprimé.</div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="msg msg-error">❌ Une erreur est survenue.</div>
    <?php endif; ?>

    <div class="appointments-grid">
        <section class="add-appointment-section">
            <div class="form-card">
                <h3>Prendre un nouveau RDV</h3>
                <form action="../process_appointment.php" method="POST">
                    <div class="form-group">
                        <label>Sujet du rendez-vous</label>
                        <input type="text" name="title" placeholder="Ex: Orientation ENSA, Aide inscription..." required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" name="date" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="form-group">
                            <label>Heure</label>
                            <input type="time" name="time" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-full">Confirmer le rendez-vous</button>
                </form>
            </div>
        </section>

        <section class="appointments-list-section">
            <div class="section-header">
                <h3>Vos rendez-vous à venir</h3>
            </div>
            
            <?php if (count($appointments) > 0): ?>
                <div class="appointments-list">
                    <?php foreach($appointments as $app): 
                        $statusClass = 'status-' . $app['status'];
                        $dateFormatted = date('d M Y', strtotime($app['appointment_date']));
                        $timeFormatted = date('H:i', strtotime($app['appointment_time']));
                    ?>
                        <div class="appointment-card">
                            <div class="app-date">
                                <span class="d-day"><?php echo date('d', strtotime($app['appointment_date'])); ?></span>
                                <span class="d-month"><?php echo date('M', strtotime($app['appointment_date'])); ?></span>
                            </div>
                            <div class="app-info">
                                <h4><?php echo htmlspecialchars($app['title']); ?></h4>
                                <p>🕒 <?php echo $timeFormatted; ?></p>
                                <span class="badge <?php echo $statusClass; ?>"><?php echo ucfirst($app['status']); ?></span>
                            </div>
                            <div class="app-actions">
                                <a href="../process_appointment.php?delete=<?php echo $app['id']; ?>" class="btn-delete" onclick="return confirm('Supprimer ce rendez-vous ?')">×</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="icon">📅</div>
                    <p>Vous n'avez pas encore de rendez-vous programmé.</p>
                </div>
            <?php endif; ?>
        </section>
    </div>
</div>

<style>
.appointments-container { max-width: 1000px; margin: 0 auto; padding: 40px 20px; }
.page-header { margin-bottom: 40px; text-align: center; }
.page-header h1 { font-size: 2.5rem; color: var(--primary); font-weight: 800; margin-bottom: 10px; }
.page-header p { color: var(--text-muted); font-size: 1.1rem; }

.appointments-grid { display: grid; grid-template-columns: 350px 1fr; gap: 40px; align-items: start; }

.form-card { background: #fff; padding: 30px; border-radius: 24px; box-shadow: var(--shadow-md); border: 1px solid var(--border-color); }
.form-card h3 { margin-bottom: 25px; color: var(--primary-dark); font-weight: 700; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }

.appointments-list { display: grid; gap: 20px; }
.appointment-card { 
    background: #fff; 
    padding: 20px; 
    border-radius: 20px; 
    display: flex; 
    align-items: center; 
    gap: 25px; 
    box-shadow: var(--shadow-sm); 
    border: 1px solid var(--border-color);
    transition: var(--transition);
}
.appointment-card:hover { transform: translateX(5px); border-color: var(--primary-light); box-shadow: var(--shadow-md); }

.app-date { background: var(--bg-light); padding: 12px; border-radius: 16px; text-align: center; min-width: 70px; }
.app-date .d-day { display: block; font-size: 1.4rem; font-weight: 800; color: var(--primary); }
.app-date .d-month { font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700; }

.app-info { flex: 1; }
.app-info h4 { font-size: 1.1rem; margin-bottom: 5px; color: var(--primary-dark); }
.app-info p { font-size: 0.9rem; color: var(--text-muted); margin-bottom: 8px; }

.status-pending { background: #fff7ed; color: #9a3412; }
.status-confirmed { background: #f0fdf4; color: #166534; }
.status-cancelled { background: #fef2f2; color: #991b1b; }

.app-actions { margin-left: auto; }
.btn-delete { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; color: #ef4444; font-size: 1.5rem; text-decoration: none; transition: var(--transition); }
.btn-delete:hover { background: #fee2e2; }

@media (max-width: 768px) {
    .appointments-grid { grid-template-columns: 1fr; }
}
</style>

<?php require "../includes/footer.php"; ?>
