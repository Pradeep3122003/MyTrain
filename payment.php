<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'] ?? null;
    $name = $_POST['name'] ?? null;
    $src = $_POST['src'] ?? null;
    $dest = $_POST['dest'] ?? null;
    $src_depar = $_POST['src_depar'] ?? null;
    $dest_arriv = $_POST['dest_arriv'] ?? null;
    $distance = $_POST['distance'] ?? null;
    $user = $_POST['user'] ?? null;
    $email = $_POST['email'] ?? null;
    $cost = $_POST['cost'] ?? null;

    // Validate session token
    if ($token !== $_SESSION['token']) {
        die("Invalid token!");
    }

    // Ensure all required fields are provided
    if (!$name || !$src || !$dest || !$src_depar || !$dest_arriv || !$distance || !$user || !$cost || !$email) {
        die("Enter all fields!");
    }

    // Prevent SQL Injection
    $train = mysqli_real_escape_string($link, $name);
    $email = mysqli_real_escape_string($link, $email);
    $src = mysqli_real_escape_string($link, $src);
    $dest = mysqli_real_escape_string($link, $dest);
    $user = mysqli_real_escape_string($link, $user);
    $date = mysqli_real_escape_string($link, $src_depar);
    $cost = mysqli_real_escape_string($link, $cost);
    // Insert booking into database
    $sql_insert = "INSERT INTO pay(name, email, train, src, dest, pin, date, book, cost)
                   VALUES (
                        '$user', 
                        '$email', 
                        '$train', 
                        '$src', 
                        '$dest', 
                        (SELECT pin FROM train WHERE src='$src' AND dest='$dest' AND name='$train' AND src_depar='$date'),
                        '$date',
                        CURRENT_TIMESTAMP,
                        '$cost'
                    )";

    if ($link->query($sql_insert) === TRUE) {
        header("Location: profile.php?" . http_build_query([
    'name' => $_SESSION['name'],
    'email' => $_SESSION['email'],
    'token' => $_SESSION['token']
]));
        exit();
    } else {
        echo "<p>Error: " . $link->error . "</p>";
    }
} else {

// Ensure session token exists
if (!isset($_SESSION['token'])) {
    die("Unauthorized access!");
}
$token = $_GET['token'] ?? null;
  if ($token !== $_SESSION['token'] || !isset($_GET['token'])) {
        die("Invalid token!");
    }

if (!isset($_GET['name']) || !isset($_GET['src']) || !isset($_GET['dest']) || !isset($_GET['src_depar']) || !isset($_GET['dest_arriv']) ||  !isset($_GET['distance']) || !isset($_GET['user']) || !isset($_GET['email'])) {
    die("Enter all fields!");
}

// Retrieve GET parameters
$token = $_GET['token'] ?? null;
$name = $_GET['name'] ?? null;
$src = $_GET['src'] ?? null;
$dest = $_GET['dest'] ?? null;
$src_depar = $_GET['src_depar'] ?? null;
$dest_arriv = $_GET['dest_arriv'] ?? null;
$distance = $_GET['distance'] ?? null;
$user = $_GET['user'] ?? null;
$email = $_GET['email'] ?? null;
// Validate token
if ($token !== $_SESSION['token']) {
    die("Invalid token!");
}

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Train Ticket Booking</title>
    <link rel="stylesheet" href="payment.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="payment-box">
            <div class="train-details">
                <h3>Train Details</h3>
                <p>Train Name: <?php echo htmlspecialchars($name); ?></p>
                <p>From: <?php echo htmlspecialchars($src); ?></p>
                <p>To: <?php echo htmlspecialchars($dest); ?></p>
                <p>Date: <?php echo htmlspecialchars($src_depar); ?></p>
                <p>Total: ₹<span id="total-price"><?php echo htmlspecialchars($distance); ?> RS</span></p>
                <div class="input-group">
                    <label for="seat-type">Seat Type</label>
<<<<<<< HEAD
                    <select id="seat-type" name="seat-type" onchange="updatePrice()">
                        <option value="<?php echo $distance; ?>">Sleeper - ₹<?php echo $distance; ?></option>
                        <option value="<?php echo $distance * 3; ?>">AC 3-Tier - ₹<?php echo $distance * 3; ?></option>
                        <option value="<?php echo $distance * 5; ?>">AC 2-Tier - ₹<?php echo $distance * 5; ?></option>
                        <option value="<?php echo $distance * 7; ?>">AC 1-Tier - ₹<?php echo $distance * 7; ?></option>
=======
                    <select id="seat-type" name="seat-type">
                        <option value="sleeper">Sleeper  <p class="inlinespan"><?php echo $distance; ?> RS</p></option>
                        <option value="ac3">AC 3-Tier  <span class="inlinespan"><?php echo $distance * 3; ?> RS</span></option>
                        <option value="ac2">AC 2-Tier  <span class="inlinespan"><?php echo $distance * 5; ?> RS</span></option>
                        <option value="ac1">AC 1-Tier  <span class="inlinespan"><?php echo $distance * 7; ?> RS</span></option>
>>>>>>> 6a0888f4850db1e96595316db800bfab04e25703
                    </select>
                </div>
            </div>

            <h2>Payment Details</h2>

            <form id="payment-form" action="payment.php" method="POST">
                <h3>Enter Card Details</h3>
                <div class="input-group">
                    <label for="card-number">Card Number</label>
                    <div class="input-with-icon">
                        <i class="fa fa-credit-card"></i>
                        <input type="text" id="card-number" name="card-number" placeholder="Enter card number" required>
                    </div>
                </div>
                <div class="input-group">
                    <label for="expiry-date">Expiry Date</label>
                    <div class="input-with-icon">
                        <i class="fa fa-calendar-alt"></i>
                        <input type="text" id="expiry-date" name="expiry-date" placeholder="MM/YY" required>
                    </div>
                </div>
                <div class="input-group">
                    <label for="cvv">CVV</label>
                    <div class="input-with-icon">
                        <i class="fa fa-lock"></i>
                        <input type="password" id="cvv" name="cvv" placeholder="Enter CVV" required>
                    </div>
                </div>

                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
                <input type="hidden" name="src" value="<?php echo htmlspecialchars($src); ?>">
                <input type="hidden" name="dest" value="<?php echo htmlspecialchars($dest); ?>">
                <input type="hidden" name="src_depar" value="<?php echo htmlspecialchars($src_depar); ?>">
                <input type="hidden" name="dest_arriv" value="<?php echo htmlspecialchars($dest_arriv); ?>">
                <input type="hidden" name="distance" value="<?php echo htmlspecialchars($distance); ?>">
                <input type="hidden" name="user" value="<?php echo htmlspecialchars($user); ?>">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <input type="hidden" name="cost" id="selected-cost" value="<?php echo htmlspecialchars($distance); ?>">

                <button class="pay-button">Pay Now</button>
            </form>

            <p class="secure-payment">
                <i class="fa fa-shield-alt"></i> Secure Payment
            </p>
        </div>
    </div>

    <script>
        function updatePrice() {
            let seatType = document.getElementById("seat-type");
            let selectedPrice = seatType.value;
            document.getElementById("total-price").innerText = "₹" + selectedPrice + " RS";
            document.getElementById("selected-cost").value = selectedPrice;
        }
    </script>
</body>
</html>

