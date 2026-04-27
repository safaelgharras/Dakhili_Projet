<?php
require "config/DataBase.php";

$pageTitle = "Home";
require "includes/header.php";
?>

<div class="hero">
    <h1>🎓 Welcome to Dakhili</h1>
    <p>Your smart guide to Moroccan universities and schools. Find the best institution for your profile, explore options, and plan your future.</p>
    <div class="hero-buttons">
        <a href="views/register.php" class="btn-hero btn-hero-primary">Get Started</a>
        <a href="views/login.php" class="btn-hero btn-hero-secondary">Login</a>
    </div>
</div>

<?php require "includes/footer.php"; ?>