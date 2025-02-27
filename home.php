<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<?php
session_start();

// Check if name and mob are passed via URL and set them in the session
if (isset($_GET['name']) && isset($_GET['email'])) {
    $_SESSION['name'] = $_GET['name'];
    $_SESSION['email'] = $_GET['email'];
    $_SESSION['token'] = $_GET['token'];
}

if (!isset($_SESSION['name']) || !isset($_SESSION['email']) || !isset($_SESSION['token'])) {
    die("Unauthorized access!");
}

// Validate the token
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check token sent with form submission (POST)
    if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
        die("Invalid token!");
    }
} else {
    // Check token in GET request (page load)
    if (!isset($_GET['token']) || $_GET['token'] !== $_SESSION['token']) {
        die("Invalid token!");
    }
}

require("db.php");

// User is authenticated
$user = $_SESSION['name'];
$email = $_SESSION['email'];


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Train Ticket Booking</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="container">
        <div class="left-section">
            <div class="logo-area">
                <img src="irctc_logo.png" alt="IRCTC Logo" class="irctc-logo">
                <span class="indian-railways-text">Indian Railways</span>
            </div>
        </div>
        <div class="right-section">
            <div class="form-box">
                <h2>BOOK <span class="highlight">TICKET</span><span class="dot">.</span></h2>
                <form>
                    <div class="input-group">
                        <label for="from">FROM</label>
                        <div class="input-with-icon">
                            <i class="fas fa-map-marker-alt"></i>
                            <input type="text" id="from" name="from" placeholder="Search departure station" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <label for="to">TO</label>
                        <div class="input-with-icon">
                            <i class="fas fa-search"></i>
                            <input type="text" id="to" name="to" placeholder="Search destination station" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <label for="date">DATE</label>
                        <div class="input-with-icon">
                            <i class="fas fa-calendar-alt"></i>
                            <input type="date" id="date" name="date" required>
                        </div>
                    </div>
                    <button type="submit">SEARCH</button>
                </form>
                <p>Don't have an account? <a href="signin.html">Create new</a></p>
            </div>
        </div>
    </div>
</body>
</html>
