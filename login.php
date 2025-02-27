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
