<?php
$pageTitle = "AI Orientation";
require "../includes/header.php";
?>

<div class="form-container">
    <h2>🤖 Find Your School</h2>
    <p style="text-align:center; color:#777; margin-bottom:20px; font-size:0.9rem;">
        Enter your profile and we'll suggest the best schools for you.
    </p>

    <form method="POST" action="../ai_process.php">
        <div class="form-group">
            <label>Bac Branch</label>
            <select name="bac_branch" required>
                <option value="">-- Select --</option>
                <option value="SVT">Sciences de la Vie et de la Terre (SVT)</option>
                <option value="PC">Physique-Chimie (PC)</option>
                <option value="Math">Sciences Mathématiques</option>
                <option value="Eco">Sciences Économiques</option>
                <option value="Tech">Sciences et Technologies</option>
                <option value="Lettres">Lettres et Sciences Humaines</option>
            </select>
        </div>

        <div class="form-group">
            <label>Bac Average</label>
            <input type="number" step="0.01" name="average" placeholder="14.50" min="0" max="20" required>
        </div>

        <div class="form-group">
            <label>Preferred City</label>
            <input type="text" name="city" placeholder="Casablanca">
        </div>

        <button type="submit" class="btn btn-primary">🔍 Get Recommendations</button>
    </form>
</div>

<?php require "../includes/footer.php"; ?>