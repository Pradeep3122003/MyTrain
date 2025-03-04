<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<?php
session_start();
require("db.php");
$exist = 0; // Initialize the variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $from = $_POST["from"];
    $to = $_POST["to"];
    $date = $_POST["date"];
    $token = $_POST["token"];
    $user = $_POST["user"];
    $email = $_POST["email"];
if (!isset($_SESSION['token']) || !isset($_POST['user']) || !isset($_POST['email'])) {
    die("Unauthorized access!");
}

if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
        die("Invalid token!");
}

if (!isset($_POST['from']) || !isset($_POST['to']) || !isset($_POST['date'])) {
        die("Fill all fields!");
}

$sql = "SELECT * FROM train WHERE src = ? AND dest = ? AND DATE(src_depar) = ?";
$stmt = $link->prepare($sql);

if ($stmt) {
    $stmt->bind_param("sss", $from, $to, $date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $exist = 1;
        $rows = []; // Store all rows
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row; // Append each row
        }
    } else {
        $exist = 2; // No match found
    }
$token = $_SESSION['token'];
    $stmt->close();
} else {
    die("Query failed: " . $link->error);
}
} else {
   die("Invalid Access");
}
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
    <?php foreach ($rows as $row) { ?>
        <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars(date("D : M : Y", strtotime($row['src_depar']))) ?><br><?= htmlspecialchars(date("H:i", strtotime($row['src_depar']))) ?><br><?= htmlspecialchars($row['src']) ?></td>
            <td><?= htmlspecialchars($row['distance']) ?> km</td>
            <td><?= htmlspecialchars(date("D : M : Y", strtotime($row['dest_arriv']))) ?><br><?= htmlspecialchars(date("H:i", strtotime($row['dest_arriv']))) ?><br><?= htmlspecialchars($row['dest']) ?></td>
            <td>
                <button class="book-btn"
                    data-name="<?= htmlspecialchars($row['name']) ?>"
                    data-src="<?= htmlspecialchars($row['src']) ?>"
                    data-dest="<?= htmlspecialchars($row['dest']) ?>"
                    data-src_depar="<?= htmlspecialchars($row['src_depar']) ?>"
                    data-dest_arriv="<?= htmlspecialchars($row['dest_arriv']) ?>"
                    data-distance="<?= htmlspecialchars($row['distance']) ?>"
                    data-user="<?= htmlspecialchars($user) ?>"
                    data-email="<?= htmlspecialchars($email) ?>">
                    Book
                </button>
            </td>
        </tr>
    <?php } ?>
<?php } else { ?>
    <p>Please do check for other dates.</p>
<?php } ?>


            </tbody>
        </table>
<script>

let buttons = document.querySelectorAll(".book-btn"); 
let token = "<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>";

buttons.forEach(button => {
    button.addEventListener("click", () => {
        let name = encodeURIComponent(button.getAttribute("data-name"));
        let src = encodeURIComponent(button.getAttribute("data-src"));
        let dest = encodeURIComponent(button.getAttribute("data-dest"));
        let src_depar = encodeURIComponent(button.getAttribute("data-src_depar"));
        let dest_arriv = encodeURIComponent(button.getAttribute("data-dest_arriv"));
        let distance = encodeURIComponent(button.getAttribute("data-distance"));
        let user = encodeURIComponent(button.getAttribute("data-user"));
        let email = encodeURIComponent(button.getAttribute("data-email"));

        window.open(`payment.php?token=${token}&name=${name}&src=${src}&dest=${dest}&src_depar=${src_depar}&dest_arriv=${dest_arriv}&distance=${distance}&user=${user}&email=${email}`, "_self");
    });
});


</script>

    </div>
</body>
</html>
