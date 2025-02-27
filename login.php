<?php
require("db.php");
session_start();
$exist = 0; // Initialize the variable
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["user2"];
    $name = mysqli_real_escape_string($link, $name);

    $pass = $_POST["pass2"];
    $pass = mysqli_real_escape_string($link, $pass);
    $pass = hash("sha1", $pass, false);

    // Check if the user already exists
    $sql = "SELECT * FROM login WHERE name = ? AND password = ?";
    $stmt = $link->prepare($sql);
    $stmt->bind_param("ss", $name, $pass);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $exist = 1;
        $sql = "SELECT mobile FROM login WHERE name = ? AND password = ?";
        $stmt = $link->prepare($sql);
        $stmt->bind_param("ss", $name, $pass);
        $stmt->execute();
        $result = $stmt->get_result();  // Get the result set

        if ($row = $result->fetch_assoc()) {  // Fetch the first row
           $mob = $row['mobile'];  // Extract the mobile number
       } else {
           $mob = null;  // No match found
       }

        header("Location: access.php?name=" . urlencode($name) . "&mob=" . urlencode($mob));
        exit();
        

    } else {
        $exist = 2;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form>
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" placeholder="Enter your username">
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" placeholder="Enter your password">
            </div>
            <button type="submit">Login</button>
            <p>Don't have an account? <a href="index.php">Create one</a></p>
        </form>
    </div>
</body>
</html>
