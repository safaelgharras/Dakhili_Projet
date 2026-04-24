<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

<h2>Welcome <?php echo $_SESSION["user_name"]; ?> 👋</h2>

<p>This is your dashboard</p>

<a href="logout.php">Logout</a>

<a href="institutions.php">View Schools</a>

<a href="saved_schools.php"> My Saved Schools</a>

</body>
</html>