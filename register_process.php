<?php
require "config/DataBase.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $bac_branch = $_POST["bac_branch"];
    $average = $_POST["average"];
    $city = $_POST["city"];

    $sql = "INSERT INTO students (name, email, password, bac_branch, average, city)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $email, $password, $bac_branch, $average, $city]);

    echo "Student registered successfully ";
}
?>