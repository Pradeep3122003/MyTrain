<?php
require("db.php");
session_start();
$exist = 0; // Initialize the variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $pass = $_POST["password"];

    // Prepare SQL to fetch email along with validation
    $sql = "SELECT email FROM login WHERE name = ? AND password = ?";
    $stmt = $link->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("ss", $name, $pass);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $exist = 1;
            $email = $row['email'];
            header("Location: access.php?name=" . urlencode($name) . "&email=" . urlencode($email));
            exit();
        } else {
            $exist = 2; // No match found
        }
        
        $stmt->close();
    } else {
        die("Query failed: " . $link->error);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="name" name="name" placeholder="Enter your username">
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password">
            </div>
            <button type="submit">Login</button>
            <p>Don't have an account? <a href="index.php"><br>Create one</a></p>
        </form>
    </div>
</body>
</html>
