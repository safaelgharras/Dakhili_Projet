<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    
    <h2>Student Register</h2>

    <form method="POST" action="../register_process.php">

        <input type="text" name="name" placeholder="Full name" required><br><br>

        <input type="email" name="email" placeholder="Email" required><br><br>

        <input type="password" name="password" placeholder="Password" required><br><br>

        <input type="text" name="bac_branch" placeholder="Bac branch" required><br><br>

        <input type="number" step="0.01" name="average" placeholder="Average"><br><br>

        <input type="text" name="city" placeholder="City"><br><br>

        <button type="submit">Register</button>
    </form>

</body>
</html>