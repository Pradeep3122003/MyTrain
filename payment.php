<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<?php
session_start();
require("db.php");

// Ensure session token exists
if (!isset($_SESSION['token'])) {
    die("Unauthorized access!");
}

if (!isset($_GET['name']) || 
    !isset($_GET['src']) || 
    !isset($_GET['dest']) || 
    !isset($_GET['src_depar']) || 
    !isset($_GET['dest_arriv']) ||  
    !isset($_GET['distance'])) {   
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

// Validate token
if ($token !== $_SESSION['token']) {
    die("Invalid token!");
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Train Ticket Booking</title>
    <link rel="stylesheet" href="payment.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="container">
        <div class="payment-box">

            <div class="train-details">
                <h3>Train Details</h3>
                <p>Train Name: <?php echo $name ?></p>
                <p>From: <?php echo $src ?></p>
                <p>To: <?php echo $dest ?></p>
                <p>Date: <?php echo $src_depar ?></p>
                <div class="input-group">
                    <label for="seat-type">Seat Type</label>
                    <select id="seat-type" name="seat-type">
                        <option value="sleeper">Sleeper  <span style="color:blue;"><?php echo $distance; ?> RS</span></option>
                        <option value="ac3">AC 3-Tier  <span style="color:blue;"><?php echo $distance * 3; ?> RS</span></option>
                        <option value="ac2">AC 2-Tier  <span style="color:blue;"><?php echo $distance * 5; ?> RS</span></option>
                        <option value="ac1">AC 1-Tier  <span style="color:blue;"><?php echo $distance * 7; ?> RS</span></option>
                    </select>
                </div>

            </div>

            <h2>Payment Details</h2>

            <div class="payment-methods">
                <button class="payment-button" onclick="showPaymentForm('card')">
                    <i class="fa fa-credit-card"></i> Credit/Debit Card
                </button>
                <button class="payment-button" onclick="showPaymentForm('netbanking')">
                    <i class="fa fa-university"></i> Net Banking
                </button>
                <button class="payment-button" onclick="showPaymentForm('upi')">
                    <i class="fa fa-mobile-alt"></i> UPI
                </button>
                <button class="payment-button" onclick="showPaymentForm('wallet')">
                    <i class="fa fa-wallet"></i> Wallets
                </button>
            </div>

            <div id="card-details" class="payment-form">
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
                <div class="input-group">
                    <label for="card-holder">Card Holder Name</label>
                    <div class="input-with-icon">
                        <i class="fa fa-user"></i>
                        <input type="text" id="card-holder" name="card-holder" placeholder="Enter card holder name" required>
                    </div>
                </div>
            </div>

            <div id="netbanking-details" class="payment-form" style="display: none;">
                <h3>Select Bank</h3>
                <div class="input-group">
                    <label for="bank-name">Bank Name</label>
                    <select id="bank-name" name="bank-name">
                        <option value="sbi">State Bank of India</option>
                        <option value="icici">ICICI Bank</option>
                        <option value="hdfc">HDFC Bank</option>
                        <option value="axis">Axis Bank</option>
                        <option value="kotak">Kotak Mahindra Bank</option>
                        <option value="pnb">Punjab National Bank</option>
                        <option value="bob">Bank of Baroda</option>
                        <option value="canara">Canara Bank</option>
                        <option value="union">Union Bank of India</option>
                        <option value="idbi">IDBI Bank</option>
                    </select>
                </div>
            </div>

            <div id="upi-details" class="payment-form" style="display: none;">
                <h3>Enter UPI ID</h3>
                <div class="input-group">
                    <label for="upi-id">UPI ID</label>
                    <div class="input-with-icon">
                        <i class="fa fa-mobile-alt"></i>
                        <input type="text" id="upi-id" name="upi-id" placeholder="Enter UPI ID" required>
                    </div>
                </div>
            </div>

            <div id="wallet-details" class="payment-form" style="display: none;">
                <h3>Select Wallet</h3>
                <div class="input-group">
                    <label for="wallet-name">Wallet Name</label>
                    <select id="wallet-name" name="wallet-name">
                        <option value="paytm">Paytm</option>
                        <option value="phonepe">PhonePe</option>
                        <option value="gpay">Google Pay</option>
                    </select>
                </div>
            </div>

            <button class="pay-button">Pay Now</button>
            <p class="secure-payment">
                <i class="fa fa-shield-alt"></i> Secure Payment
            </p>
        </div>
    </div>

    <script>
        function showPaymentForm(formId) {
            // Hide all payment forms
            document.querySelectorAll('.payment-form').forEach(form => {
                form.style.display = 'none';
            });

            // Show the selected payment form
            document.getElementById(formId + '-details').style.display = 'block';
        }

        // Show card details by default
        showPaymentForm('card');
    </script>
</body>
</html>

