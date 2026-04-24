<?php
require "config/DataBase.php";

$sql = "SELECT * FROM institutions 
        ORDER BY min_average ASC
        LIMIT 10";

$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();

echo "<h2> Suggested Schools:</h2>";

foreach($results as $r){

    echo "<div style='border:1px solid black; padding:10px; margin:10px;'>";

    echo "<h3>" . $r["name"] . "</h3>";
    echo "<p>City: " . $r["city"] . "</p>";
    echo "<p>Min Average: " . $r["min_average"] . "</p>";
    echo "<p><b>Requirements:</b> " . $r["requirements"] . "</p>";

    echo "</div>";
}
?>