<?php
require "config/DataBase.php";
$res = $pdo->query("SELECT name, min_average, seuil FROM institutions LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
print_r($res);
?>
