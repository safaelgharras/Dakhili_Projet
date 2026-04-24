<?php
session_start();
require "../config/DataBase.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION["user_id"];

$sql = "SELECT institutions.*
        FROM saved_schools
        JOIN institutions 
        ON saved_schools.institution_id = institutions.id
        WHERE saved_schools.student_id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$student_id]);

$schools = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Saved Schools</title>
</head>
<body>

<h2>⭐ My Saved Schools</h2>

<?php if(count($schools) == 0){ ?>
    <p>No saved schools yet.</p>
<?php } ?>

<?php foreach($schools as $s){ ?>

    <div style="border:1px solid black; margin:10px; padding:10px;">
        
        <h3><?php echo $s["name"]; ?></h3>
        <p>City: <?php echo $s["city"]; ?></p>
        <p>Type: <?php echo $s["type"]; ?></p>
        <p>Min Average: <?php echo $s["min_average"]; ?></p>
        <p><?php echo $s["description"]; ?></p>
        <p><b>Requirements:</b> <?php echo $s["requirements"]; ?></p>

    </div>

<?php } ?>

</body>
</html>