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
            <h2><br>Trains Available</br></h2>
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
                <tr>
                    <td>Narasapur - SMVT Bengaluru Special</td>
                    <td>21:00<br>BGM, Belagavi</td>
                    <td>10hr 25min<br>13 stops, 611 km</td>
                    <td>07:25<br>SBC, Bengaluru</td>
                    <td><button class="book-btn">Book</button></td>
                </tr>
                <tr>
                    <td>Belagavi Yesvantpur Express</td>
                    <td>21:35<br>BGM, Belagavi</td>
                    <td>10hr 55min<br>16 stops, 606 km</td>
                    <td>08:30<br>YPR, Yesvantpur</td>
                    <td><button class="book-btn">Book</button></td>
                </tr>
                <tr>
                    <td>Belagavi KSR Bengaluru Express</td>
                    <td>21:00<br>BGM, Belagavi</td>
                    <td>10hr 30min<br>13 stops, 611 km</td>
                    <td>07:30<br>SBC, Bengaluru</td>
                    <td><button class="book-btn">Book</button></td>
                </tr>
                <tr>
                    <td>Vishwamanav Express</td>
                    <td>05:45<br>BGM, Belagavi</td>
                    <td>14hr 55min<br>33 stops, 749 km</td>
                    <td>20:40<br>MYS, Mysuru</td>
                    <td><button class="book-btn">Book</button></td>
                </tr>
                <tr>
                    <td>Belagavi Mugr Special</td>
                    <td>13:10<br>BGM, Belagavi</td>
                    <td>23hr 40min<br>36 stops, 1143 km</td>
                    <td>12:50<br>MUGR, Manuguru</td>
                    <td><button class="book-btn">Book</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
