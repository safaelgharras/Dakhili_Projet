<?php
require "config/DataBase.php";
$sql = file_get_contents("database/map_villes.sql");
$pdo->exec($sql);
echo "Cities mapped.";
?>
