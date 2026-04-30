<?php
require 'config/DataBase.php';
$stmt = $pdo->query('SELECT DISTINCT type FROM institutions;');
print_r($stmt->fetchAll(PDO::FETCH_COLUMN));
$stmt = $pdo->query('SELECT DISTINCT nom FROM filieres;');
print_r($stmt->fetchAll(PDO::FETCH_COLUMN));
