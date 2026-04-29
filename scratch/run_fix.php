<?php
require "config/DataBase.php";
$sql = file_get_contents("database/fix_seuil.sql");
$pdo->exec($sql);
echo "Seuil fixed.";
?>
