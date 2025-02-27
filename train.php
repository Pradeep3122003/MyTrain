<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<?php
require("db.php");
$exist = 0; // Initialize the variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $from = $_POST["from"];
    $to = $_POST["to"];
    $date = $_POST["date"];


//if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
  //      die("Invalid token!");
//}

if (!isset($_POST['from']) || !isset($_POST['to']) || !isset($_POST['date'])) {
        die("Fill all fields!");
}

$sql = "SELECT * FROM train WHERE src = ? AND dest = ? AND DATE(src_depar) = ?";
$stmt = $link->prepare($sql);

if ($stmt) {
    $stmt->bind_param("sss", $from, $to, $date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $exist = 1;
    } else {
        $exist = 2; // No match found
    }

    $stmt->close();
} else {
    die("Query failed: " . $link->error);
}

}    // Prepare SQL to fetch email along with validation
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Trains</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
            background: url('image/image.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8); /* Dark overlay */
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 1;
            padding: 20px;
            max-width: 1200px;
            margin: 20px auto;
            background: rgba(26, 26, 26, 0.682);
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            margin-top: 20vh;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            font-weight: 700;
        }

        .train-table {
            width: 100%;
            border-collapse: collapse;
        }

        .train-table thead {
            background: orange;
            color: white;
        }

        .train-table th, .train-table td {
            padding: 10px;
            text-align: left;
        }

        .train-table tbody tr {
            background: rgba(255, 255, 255, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .train-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .train-table tbody td {
            color: white;
        }

        .book-btn {
            padding: 8px 16px;
            background: orange;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
        }

        .book-btn:hover {
            background: #e67e22;
        }

        @media (max-width: 768px) {
            .train-table th, .train-table td {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><br>
<?php if ($exist == 2) { ?>No <?php } ?>
Trains Available</br></h2>
        </div>
        <table class="train-table">
            <thead>
                <tr>
                    <th>Train Details</th>
                    <th>Source Departure</th>
                    <th>Journey Details</th>
                    <th>Destination Arrival</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($exist == 1) { ?>

        <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars(date("H:i", strtotime($row['src_depar']))) ?><br><?= htmlspecialchars($row['src']) ?></td>
            <td><?= htmlspecialchars($row['distance']) ?> km</td>
            <td><?= htmlspecialchars(date("H:i", strtotime($row['dest_arriv']))) ?><br><?= htmlspecialchars($row['dest']) ?></td>
            <td><button class="book-btn" id="paybtn">Book</button></td>
        </tr>

<?php } else { ?>
    <p>Please do check for other dates.</p>
<?php } ?>
            </tbody>
        </table>
<script>
let login =document.getElementById("paybtn")
login.onclick = () => {
    window.open("payment.php", "_self");
}</script>

    </div>
</body>
</html>
