<?php
$pageTitle = "Login";
require "../includes/header.php";
?>

<div class="form-container">
    <h2>🔐 Login</h2>

    <?php if (isset($_GET['error'])): ?>
        <div class="msg msg-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <div class="msg msg-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>

    <form method="POST" action="../login_process.php">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="your@email.com" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn btn-primary">Login</button>
    </form>

    <div class="form-footer">
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</div>

<?php require "../includes/footer.php"; ?>