<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<?php
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


if ($token !== $_SESSION['token']) {
    die("Invalid token!");
}

//if ($user !== $_SESSION['user'] || $email !== $_SESSION['email']) {
//    die("Unauthorised access!");
//}

if (!isset($_POST['name']) ||
    !isset($_POST['src']) ||
    !isset($_POST['dest']) ||
    !isset($_POST['src_depar']) ||
    !isset($_POST['dest_arriv']) ||
    !isset($_POST['distance']) || 
    !isset($_POST['user']) ||
    !isset($_POST['email'])){
    die("Enter all fields!");
}


       //$name = $_POST["name"];
        //$name = mysqli_real_escape_string($link, $name);

        //$email = $_POST["email"];
        //$email = mysqli_real_escape_string($link, $email);

        //$pass = $_POST["password"];
        //$pass = mysqli_real_escape_string($link, $pass);

        // Check if the user already exists
        //$sql = "SELECT * FROM login WHERE email = '$email'";

        //$result = $link->query($sql);

        //if ($result->num_rows > 0) {
        //    $exist = 1;
        //} else {
            // Insert new user into the database
          //  $sql_insert = "INSERT INTO login(name,email,password) VALUES ('$name', '$email', '$pass')";
            //if ($link->query($sql_insert) === TRUE) {
              //  $exist = 2;
                //header("Location: login.php");

            //} else {
              //  echo "<p>Error: " . $link->error . "</p>";
            //}
        
    }  else {
// Ensure session token exists
if (!isset($_SESSION['token'])) {
    die("Unauthorized access!");
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
<select id="seat-type" name="seat-type" onchange="updatePrice()">
    <option value="<?php echo $distance; ?>">Sleeper - <?php echo $distance; ?> RS</option>
    <option value="<?php echo $distance * 3; ?>">AC 3-Tier - <?php echo $distance * 3; ?> RS</option>
    <option value="<?php echo $distance * 5; ?>">AC 2-Tier - <?php echo $distance * 5; ?> RS</option>
    <option value="<?php echo $distance * 7; ?>">AC 1-Tier - <?php echo $distance * 7; ?> RS</option>
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

            <form id="card-details" class="payment-form" action="payment.php" method="POST">
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
                    <button class="pay-button">Pay Now</button>
            </form>

            <form id="netbanking-details" class="payment-form" style="display: none;" action="payment.php" method="POST">
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
                 <button class="pay-button">Pay Now</button>
            </form>

            <form id="upi-details" class="payment-form" style="display: none;" action="payment.php" method="POST">
                <h3>Enter UPI ID</h3>
                <div class="input-group">
                    <label for="upi-id">UPI ID</label>
                    <div class="input-with-icon">
                        <i class="fa fa-mobile-alt"></i>
                        <input type="text" id="upi-id" name="upi-id" placeholder="Enter UPI ID" required>
                    </div>
                </div>
                 <input type="hidden" name="token" value=<?php echo $token ?>>
                 <input type="hidden" name="name" value=<?php echo $name ?>>
                 <input type="hidden" name="src" value=<?php echo $src ?>>
                  <input type="hidden" name="dest" value=<?php echo $dest ?>>
                  <input type="hidden" name="src_depar" value=<?php echo $src_depar ?>>
                 <input type="hidden" name="dest_arriv" value=<?php echo $dest_arriv ?>>
                  <input type="hidden" name="distance" value=<?php echo $distance ?>>
                 <input type="hidden" name="user" value=<?php echo $user ?>>
                  <input type="hidden" name="email" value=<?php echo $email ?>>
                <button class="pay-button">Pay Now</button>
            </form>

            <form id="wallet-details" class="payment-form" style="display: none;" action="payment.php" method="POST">
                <h3>Select Wallet</h3>
                <div class="input-group">
                    <label for="wallet-name">Wallet Name</label>
                    <select id="wallet-name" name="wallet-name">
                        <option value="paytm">Paytm</option>
                        <option value="phonepe">PhonePe</option>
                        <option value="gpay">Google Pay</option>
                    </select>
                </div>
 <button class="pay-button">Pay Now</button>
            </form>

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

