<?php
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: page.php");
    exit();
}

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "login";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Fetch user details
$sql = "SELECT * FROM receipient WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #ffe6e6, #fff0f0);
        }

        .profile-page {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            color: #b30000;
            font-size: 36px;
        }

        .card {
            background-color: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            max-width: 1000px;
            width: 100%;
            margin: 0 auto;
        }

        .user-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px 40px;
        }

        .info-box {
            background-color: #ffecec;
            padding: 20px;
            border-radius: 15px;
            box-shadow: inset 0 0 5px rgba(0,0,0,0.05);
        }

        .label {
            font-weight: bold;
            color: #800000;
            margin-bottom: 5px;
            font-size: 16px;
        }

        .value {
            color: #333;
            font-size: 18px;
        }

        .button-wrapper {
            text-align: center;
            margin-top: 40px;
        }

        .logout-btn {
            background-color: #ff1a1a;
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: #cc0000;
        }

        @media (max-width: 600px) {
            .header h1 {
                font-size: 28px;
            }

            .card {
                padding: 20px;
            }

            .logout-btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="profile-page">
    <div class="header">
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
    </div>

    <div class="card">
        <div class="user-info">
            <div class="info-box">
                <div class="label">User Name:</div>
                <div class="value"><?php echo htmlspecialchars($user['username']); ?></div>
            </div>
            <div class="info-box">
                <div class="label">Full Name:</div>
                <div class="value"><?php echo htmlspecialchars($user['name']); ?></div>
            </div>
            <div class="info-box">
                <div class="label">Phone:</div>
                <div class="value"><?php echo htmlspecialchars($user['phone']); ?></div>
            </div>
            <div class="info-box">
                <div class="label">Age:</div>
                <div class="value"><?php echo htmlspecialchars($user['age']); ?></div>
            </div>
            <div class="info-box">
                <div class="label">Gender:</div>
                <div class="value"><?php echo htmlspecialchars($user['gender']); ?></div>
            </div>
            <div class="info-box">
                <div class="label">Email:</div>
                <div class="value"><?php echo htmlspecialchars($user['email']); ?></div>
            </div>
            <div class="info-box">
                <div class="label">Address:</div>
                <div class="value"><?php echo htmlspecialchars($user['address']); ?></div>
            </div>
        </div>

        <div class="button-wrapper">
            <form action="dashboard.php" method="POST">
                <button type="submit" class="logout-btn">Back to Dashboard</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
