<?php
require "config/DataBase.php";

echo "<h1>Migration: Language Support</h1>";

try {
    // Add columns to institutions
    $pdo->exec("ALTER TABLE institutions 
                ADD COLUMN IF NOT EXISTS name_ar VARCHAR(255) NULL AFTER name,
                ADD COLUMN IF NOT EXISTS name_en VARCHAR(255) NULL AFTER name_ar,
                ADD COLUMN IF NOT EXISTS description_ar TEXT NULL AFTER description,
                ADD COLUMN IF NOT EXISTS description_en TEXT NULL AFTER description_ar");
    
    // Add columns to filieres
    $pdo->exec("ALTER TABLE filieres 
                ADD COLUMN IF NOT EXISTS nom_ar VARCHAR(255) NULL AFTER nom,
                ADD COLUMN IF NOT EXISTS nom_en VARCHAR(255) NULL AFTER nom_ar,
                ADD COLUMN IF NOT EXISTS description_ar TEXT NULL AFTER description,
                ADD COLUMN IF NOT EXISTS description_en TEXT NULL AFTER description_ar");

    // Copy initial data so it's not empty, just to fallback
    $pdo->exec("UPDATE institutions SET name_ar = name, name_en = name WHERE name_ar IS NULL");
    $pdo->exec("UPDATE institutions SET description_ar = description, description_en = description WHERE description_ar IS NULL");
    
    $pdo->exec("UPDATE filieres SET nom_ar = nom, nom_en = nom WHERE nom_ar IS NULL");
    $pdo->exec("UPDATE filieres SET description_ar = description, description_en = description WHERE description_ar IS NULL");

    echo "<p style='color:green;'>Migration successful. Columns name_ar, name_en, description_ar, description_en added and populated with fallback data.</p>";
} catch (Exception $e) {
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
}
?>
