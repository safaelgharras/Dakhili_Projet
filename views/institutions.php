<?php
session_start();
require "../config/DataBase.php";

$sql = "SELECT * FROM institutions";
$stmt = $pdo->query($sql);
$institutions = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Institutions</title>
</head>
<body>

<h2>List of Schools</h2>

<p>Requirements: <?php echo $inst["requirements"]; ?></p>

<?php foreach($institutions as $inst){ ?>

    <div style="border:1px solid black; margin:10px; padding:10px;">
        <h3><?php echo $inst["name"]; ?></h3>
        <p>City: <?php echo $inst["city"]; ?></p>
        <p>Type: <?php echo $inst["type"]; ?></p>
        <p>Min Average: <?php echo $inst["min_average"]; ?></p>
        <p><?php echo $inst["description"]; ?></p>
    </div>

<?php } ?>

</body>
</html>