<?php
session_start();
require "config/DataBase.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $sql = "SELECT * FROM students WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);

    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_name"] = $user["name"];

        header("Location: views/dashboard.php");
        exit();
        
    } else {
        header("Location: views/login.php?error=Invalid email or password");
        exit();
    }
}
?>