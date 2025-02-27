<?php
session_start();

// Check if name and mob are passed via URL and set them in the session
if (isset($_GET['name']) && isset($_GET['email'])) {
    $_SESSION['name'] = $_GET['name'];
    $_SESSION['email'] = $_GET['email'];
}

// Check if session variables are set
if (!isset($_SESSION['name']) || !isset($_SESSION['email'])) {
    die("Unauthorized access!");
}

// Ensure the token exists
if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
    $_SESSION['token_time'] = time();
}


// Check if the token is expired (e.g., expire after 30 minutes)
$token_lifetime = 30 * 60;  // 30 minutes in seconds
if (isset($_SESSION['token_time']) && (time() - $_SESSION['token_time'] > $token_lifetime)) {
    session_destroy();
    die("Token has expired. Please log in again.");
}


// Redirect to chat.php with token
header("Location: train.php?name=" . urlencode($_SESSION['name']) . "&email=" . urlencode($_SESSION['email']) . "&token=" . $_SESSION['token']);
exit();
?>
