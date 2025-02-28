<?php
session_start();
require("db.php");

// Store POST data in session
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['token'])) {
        $_SESSION['name'] = $_POST['name'];
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['token'] = $_POST['token'];
    } else {

         die("Invaid Access");
}
} else {

if (isset($_GET['name']) && isset($_GET['email']) && isset($_GET['token'])) {
        $_SESSION['name'] = $_GET['name'];
        $_SESSION['email'] = $_GET['email'];
        $_SESSION['token'] = $_GET['token'];
    } else {

         die("Invaid Access");
}

}

// Check session values
if (!isset($_SESSION['name']) || !isset($_SESSION['email']) || !isset($_SESSION['token'])) {
    die("Unauthorized access!");
}

$user = $_SESSION['name'];
$email = $_SESSION['email'];
$token = $_SESSION['token'];

// Fetch tickets from DB
$sql = "SELECT * FROM pay WHERE name = ? AND email = ?";
$stmt = $link->prepare($sql);
$exist = 2;
$rows = [];

if ($stmt) {
    $stmt->bind_param("ss", $user, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $exist = 1;
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
    $stmt->close();
} else {
    die("Query failed: " . $link->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="overlay">
            <div class="profile-content">
                <h1 style="color: white;">Hey, <span id="username"><?php echo htmlspecialchars($user); ?></span>!</h1>
                <h3>Your Ticket History</h3>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Date</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($exist == 1): ?>
                            <?php foreach ($rows as $index => $row): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($row['src']) ?></td>
                                    <td><?= htmlspecialchars($row['dest']) ?></td>
                                    <td><?= htmlspecialchars($row['date']) ?></td>
                                    <td>
                                        <button class="details-btn" onclick="showDetails(
                                            '<?= htmlspecialchars($row['src']) ?>',
                                            '<?= htmlspecialchars($row['dest']) ?>',
                                            '<?= htmlspecialchars($row['date']) ?>',
                                            '<?= htmlspecialchars($row['book']) ?>',
                                            '<?= htmlspecialchars($row['cost']) ?>',
                                            '<?= htmlspecialchars($row['pin']) ?>',
                                            '<?= htmlspecialchars($row['train']) ?>'
                                        )">View</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No tickets found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <button class="book-btn" onclick="location.href='home.php?name=<?= htmlspecialchars($user) ?>&email=<?= htmlspecialchars($email) ?>&token=<?= htmlspecialchars($token) ?>'">Book New Ticket</button>
            </div>
        </div>
    </div>

    <!-- Popup for Ticket Details -->
    <div id="details-popup" class="popup hidden">
        <div class="popup-content">
            <h3 style="align-items: center;">Ticket Details</h3>
            <table border="1" style="border-collapse: collapse; width: 50%; text-align: left;">
                <thead>
                    <tr>
                        <th>Detail</th>
                        <th>Information</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Train</strong></td>
                        <td><span id="ticket-name"></span></td>
                    </tr>
                    <tr>
                        <td><strong>No</strong></td>
                        <td><span id="ticket-pin"></span></td>
                    </tr>
                    <tr>
                        <td><strong>From</strong></td>
                        <td><span id="ticket-from"></span></td>
                    </tr>
                    <tr>
                        <td><strong>To</strong></td>
                        <td><span id="ticket-to"></span></td>
                    </tr>
                    <tr>
                        <td><strong>Travel Date</strong></td>
                        <td><span id="ticket-date"></span></td>
                    </tr>
                    <tr>
                        <td><strong>Booking Date</strong></td>
                        <td><span id="ticket-booking-date"></span></td>
                    </tr>
                    <tr>
                        <td><strong>Price</strong></td>
                        <td>₹<span id="ticket-price"></span></td>
                    </tr>
                </tbody>
            </table>
            <button class="close-btn" onclick="closePopup()">Close</button>
        </div>
    </div>

    <script>
        function showDetails(from, to, travelDate, bookingDate, price, pin, name) {
            document.getElementById('ticket-from').innerText = from;
            document.getElementById('ticket-to').innerText = to;
            document.getElementById('ticket-date').innerText = travelDate;
            document.getElementById('ticket-booking-date').innerText = bookingDate;
            document.getElementById('ticket-price').innerText = price;
            document.getElementById('ticket-pin').innerText = pin;
            document.getElementById('ticket-name').innerText = name;

            document.getElementById('details-popup').classList.remove('hidden');
        }

        function closePopup() {
            document.getElementById('details-popup').classList.add('hidden');
        }
    </script>
</body>
</html>
