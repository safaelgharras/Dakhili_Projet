<?php
require "config/DataBase.php";

echo "<h1>Maslaki - Database Migration</h1>";

try {
    $files = ["database/schema_update.sql", "database/features_update.sql"];
    
    foreach ($files as $file) {
        if (file_exists($file)) {
            $sql = file_get_contents($file);
            $pdo->exec($sql);
            echo "<p>Running $file... Done.</p>";
        }
    }
    
    echo "<p style='color:green;'>✅ Migration successful! Your database is now up to date.</p>";
    echo "<a href='index.php'>Go to Home</a>";
} catch (PDOException $e) {
    echo "<p style='color:red;'>❌ Migration failed: " . $e->getMessage() . "</p>";
    echo "<p>Please ensure your database 'maslaki' exists and is accessible.</p>";
}
?>
