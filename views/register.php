<?php
$pageTitle = "Inscription";
require "../includes/header.php";
?>

<div class="auth-split">
    <div class="auth-image">
        <h2>Commencez votre aventure</h2>
        <p>Accédez aux meilleurs établissements du Maroc et recevez des conseils personnalisés pour votre parcours.</p>
        <div style="margin-top:40px; font-size:3rem; opacity:0.2;">🚀</div>
    </div>
    <div class="auth-form-side">
        <div class="auth-header">
            <h3>Inscription Étudiant</h3>
            <p>Remplissez les informations ci-dessous pour créer votre profil.</p>
        </div>

        <form method="POST" action="../register_process.php">
            <div class="form-group">
                <label>Nom complet</label>
                <input type="text" name="name" placeholder="Ex: Ahmed Alaoui" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="ahmed@exemple.com" required>
            </div>

            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" placeholder="Minimum 8 caractères" required>
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
                <div class="form-group">
                    <label>Branche Bac</label>
                    <select name="bac_branch" required>
                        <option value="Sciences Math">Sciences Math</option>
                        <option value="PC">PC</option>
                        <option value="SVT">SVT</option>
                        <option value="Economie">Economie</option>
                        <option value="Technique">Technique</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Moyenne Bac</label>
                    <input type="number" step="0.01" name="average" placeholder="Ex: 15.5" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-full" style="padding: 14px;">Créer mon compte</button>
        </form>

        <div class="form-footer">
            <p>Déjà membre ? <a href="login.php">Se connecter</a></p>
        </div>
    </div>
</div>

<?php require "../includes/footer.php"; ?>