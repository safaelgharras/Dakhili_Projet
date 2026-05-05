<?php
require "config/DataBase.php";

$empty = $pdo->query("SELECT id, name FROM institutions WHERE image IS NULL OR TRIM(image) = ''")->fetchAll();
$default = $pdo->query("SELECT id, name FROM institutions WHERE image = 'default_school.jpg' OR image = 'default-school.jpg'")->fetchAll();
$dups = $pdo->query("SELECT image, GROUP_CONCAT(name SEPARATOR ', ') as names, GROUP_CONCAT(id SEPARATOR ', ') as ids FROM institutions WHERE image IS NOT NULL AND TRIM(image) != '' AND image != 'default_school.jpg' AND image != 'default-school.jpg' GROUP BY image HAVING COUNT(*) > 1")->fetchAll();

echo "### 1. Images vides\n";
if (count($empty) == 0) echo "Aucune institution avec image vide.\n";
foreach($empty as $r) echo "- " . $r['name'] . " (id: " . $r['id'] . ") -> image vide\n";

echo "\n### 2. Images par défaut\n";
if (count($default) == 0) echo "Aucune institution avec image par défaut.\n";
foreach($default as $r) echo "- " . $r['name'] . " (id: " . $r['id'] . ") -> image par défaut\n";

echo "\n### 3. Images dupliquées\n";
if (count($dups) == 0) echo "Aucune image dupliquée.\n";
foreach($dups as $r) {
    $names = explode(", ", $r['names']);
    $ids = explode(", ", $r['ids']);
    for($i=0; $i<count($names); $i++) {
        $other_names = array_diff($names, [$names[$i]]);
        echo "- " . $names[$i] . " (id: " . $ids[$i] . ") -> image dupliquée avec " . implode(", ", $other_names) . "\n";
    }
}
?>
