<?php
require("db.php");
$exist = 0;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
        $name = $_POST["name"];
        $name = mysqli_real_escape_string($link, $name);

        $email = $_POST["email"];
        $email = mysqli_real_escape_string($link, $email);

        $pass = $_POST["pass"];
        $pass = mysqli_real_escape_string($link, $pass);
        $pass = hash("sha1", $pass, false);

        // Check if the user already exists
        $sql = "SELECT * FROM login WHERE email = '$email'";

        $result = $link->query($sql);

        if ($result->num_rows > 0) {
            $exist = 1;
        } else {
            // Insert new user into the database
            $sql_insert = "INSERT INTO login(name,email,password) VALUES ('$name', '$email', '$pass')";
            if ($link->query($sql_insert) === TRUE) {
                $exist = 2;
                header("Location: login.php");

            } else {
                echo "<p>Error: " . $link->error . "</p>";
            }
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>railsaathi</title>
    <link rel="stylesheet" href="signin.css">
</head>
<body>
    <div class="container">
        <h2>Create Account</h2>
        <form action="index.php" method="POST">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Create Password</label>
                <input type="password" id="password" name="password" placeholder="Create a password" required>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirm Password</label>
                <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your password" required>
            </div>
            <button type="submit" class="btn">Sign Up</button>
        </form>
        <div class="footer">
            <?php 
                 if("$exist" === "1"){
                    echo "<p>User already exist</p>";
                 }
             ?>
            Already have an account? <a href="#" id="btn">Login</a>
        </div>
    </div>
    <script>
let login =document.getElementById("btn")
login.onclick = () => {
    window.open("login.php", "_self");
}</script>
</body>
</html>
