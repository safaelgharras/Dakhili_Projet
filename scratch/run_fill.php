<?php
require "config/DataBase.php";
$sql = file_get_contents("database/fill_info.sql");
$pdo->exec($sql);
echo "Information filled.";
?>
