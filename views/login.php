<?php
$pageTitle = "Connexion";
require "../includes/header.php";
?>

<div class="auth-split">
    <div class="auth-image">
        <h2>Bienvenue sur Maslaki</h2>
        <p>Rejoignez des milliers d'étudiants qui façonnent leur avenir universitaire dès aujourd'hui.</p>
        <div style="margin-top:40px; font-size:3rem; opacity:0.2;">🎓</div>
    </div>
    <div class="auth-form-side">
        <div class="auth-header">
            <h3>Connexion</h3>
            <p>Heureux de vous revoir ! Veuillez entrer vos accès.</p>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="msg msg-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="msg msg-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>

        <form method="POST" action="../login_process.php">
            <div class="form-group">
                <label>Email professionnel ou personnel</label>
                <input type="email" name="email" placeholder="nom@exemple.com" required>
            </div>

            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary btn-full" style="padding: 14px;">Accéder à mon espace</button>
        </form>

        <div class="form-footer">
            <p>Nouveau sur la plateforme ? <a href="register.php">Créer un compte gratuitement</a></p>
        </div>
    </div>
</div>

<?php require "../includes/footer.php"; ?>