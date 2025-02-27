<?php
session_start();

// Check if name and email are passed via URL and set them in the session
if (isset($_GET['name']) && isset($_GET['email'])) {
    $_SESSION['name'] = $_GET['name'];
    $_SESSION['email'] = $_GET['email'];
}

// Check if session variables are set
if (!isset($_SESSION['name']) || !isset($_SESSION['email'])) {
    http_response_code(403);
    die("Unauthorized access!");
}

// Ensure the token exists only once per session
if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
    $_SESSION['token_time'] = time();
}

// Token expiration logic (30 minutes)
$token_lifetime = 30 * 60; // 30 minutes in seconds
if (isset($_SESSION['token_time']) && (time() - $_SESSION['token_time'] > $token_lifetime)) {
    session_unset();  // Clear session variables
    session_destroy(); // Destroy session
    die("Token has expired. Please log in again.");
}

// Redirect to train.php with token
header("Location: train.php?" . http_build_query([
    'name' => $_SESSION['name'],
    'email' => $_SESSION['email'],
    'token' => $_SESSION['token']
]));
exit();
?>
