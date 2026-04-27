<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Detect base path (works from root or views folder)
$isInViews = strpos($_SERVER['PHP_SELF'], '/views/') !== false;
$base = $isInViews ? '../' : '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dakhili — <?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Orientation'; ?></title>
    <link rel="stylesheet" href="<?php echo $base; ?>assets/css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <a href="<?php echo $base; ?>index.php" class="nav-logo">🎓 Dakhili</a>

        <ul class="nav-links">
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="<?php echo $base; ?>views/dashboard.php">Dashboard</a></li>
                <li><a href="<?php echo $base; ?>views/institutions.php">🏫 Schools</a></li>
                <li><a href="<?php echo $base; ?>views/saved_schools.php">⭐ Saved</a></li>
                <li><a href="<?php echo $base; ?>views/ai_form.php">🤖 AI</a></li>
                <li class="nav-user">
                    <span>👤 <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                    <a href="<?php echo $base; ?>views/logout.php" class="btn-logout">Logout</a>
                </li>
            <?php else: ?>
                <li><a href="<?php echo $base; ?>views/login.php">Login</a></li>
                <li><a href="<?php echo $base; ?>views/register.php" class="btn-register">Register</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<main class="main-content">
