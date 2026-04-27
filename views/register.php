<?php
$pageTitle = "Inscription";
require "../includes/header.php";
?>

<div class="form-container">
    <h2>Créer un compte</h2>

    <?php if (isset($_GET['error'])): ?>
        <div class="msg msg-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <form method="POST" action="../register_process.php">
        <div class="form-group">
            <label>Nom complet</label>
            <input type="text" name="name" placeholder="Mohammed Alami" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="votre@email.com" required>
        </div>

        <div class="form-group">
            <label>Mot de passe</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>

        <div class="form-group">
            <label>Filière du Bac</label>
            <select name="bac_branch" required>
                <option value="">-- Choisir --</option>
                <option value="SVT">Sciences de la Vie et de la Terre (SVT)</option>
                <option value="PC">Physique-Chimie (PC)</option>
                <option value="Math">Sciences Mathématiques</option>
                <option value="Eco">Sciences Économiques</option>
                <option value="Tech">Sciences et Technologies</option>
                <option value="Lettres">Lettres et Sciences Humaines</option>
            </select>
        </div>

        <div class="form-group">
            <label>Moyenne du Bac</label>
            <input type="number" step="0.01" name="average" placeholder="14.50" min="0" max="20" required>
        </div>

        <div class="form-group">
            <label>Ville</label>
            <input type="text" name="city" placeholder="Casablanca" required>
        </div>

        <button type="submit" class="btn btn-orange">S'inscrire</button>
    </form>

    <div class="form-footer">
        <p>Déjà inscrit ? <a href="login.php">Se connecter</a></p>
    </div>
</div>

<?php require "../includes/footer.php"; ?>