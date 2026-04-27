<?php
$pageTitle = "Register";
require "../includes/header.php";
?>

<div class="form-container">
    <h2>📝 Student Registration</h2>

    <?php if (isset($_GET['error'])): ?>
        <div class="msg msg-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <form method="POST" action="../register_process.php">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" placeholder="Mohammed Alami" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="your@email.com" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>

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
            <label>City</label>
            <input type="text" name="city" placeholder="Casablanca" required>
        </div>

        <button type="submit" class="btn btn-primary">Register</button>
    </form>

    <div class="form-footer">
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</div>

<?php require "../includes/footer.php"; ?>