<?php
require "config/DataBase.php";

echo "<h1>Maslaki - Database Migration</h1>";

try {
    $sql = file_get_contents("database/schema_update.sql");
    
    // Split by semicolons, but be careful with multi-line statements
    // This is a simple parser for the migration script
    $pdo->exec($sql);
    
    echo "<p style='color:green;'>✅ Migration successful! Your database is now up to date.</p>";
    echo "<a href='index.php'>Go to Home</a>";
} catch (PDOException $e) {
    echo "<p style='color:red;'>❌ Migration failed: " . $e->getMessage() . "</p>";
    echo "<p>Please ensure your database 'maslaki' exists and is accessible.</p>";
}
?>
