<?php
$pageTitle = "Orientation IA";
require "../includes/header.php";
?>

<div class="form-container">
    <h2>🤖 Orientation personnalisée</h2>
    <p style="text-align:center; color:var(--text-muted); margin-bottom:20px; font-size:0.85rem;">
        Entre ton profil et on te suggère les meilleures écoles pour toi.
    </p>

    <form method="POST" action="../ai_process.php">
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
            <label>Ville préférée</label>
            <input type="text" name="city" placeholder="Casablanca">
        </div>

        <button type="submit" class="btn btn-orange">Obtenir les recommandations</button>
    </form>
</div>

<?php require "../includes/footer.php"; ?>