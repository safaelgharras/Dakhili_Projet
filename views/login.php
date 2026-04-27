<?php
$pageTitle = "Connexion";
require "../includes/header.php";
?>

<div class="form-container">
    <h2>Connexion</h2>

    <?php if (isset($_GET['error'])): ?>
        <div class="msg msg-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <div class="msg msg-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>

    <form method="POST" action="../login_process.php">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="votre@email.com" required>
        </div>

        <div class="form-group">
            <label>Mot de passe</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn btn-orange">Se connecter</button>
    </form>

    <div class="form-footer">
        <p>Pas encore de compte ? <a href="register.php">Créer un compte</a></p>
    </div>
</div>

<?php require "../includes/footer.php"; ?>