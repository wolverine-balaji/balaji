<?php
session_start();
$username = $_SESSION['username'] ?? 'Guest';
$userId = $_SESSION['user_id'] ?? 0;

$upcomingDrives = [];
$message = '';

// Database connection
$conn = new mysqli("localhost", "root", "", "login");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch upcoming donation drives
$today = date('Y-m-d');
$result = $conn->query("SELECT * FROM donation_drives WHERE date >= '$today' ORDER BY date ASC");

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $upcomingDrives[] = $row;
    }
}

// Get the latest drive
$latestDrive = !empty($upcomingDrives) ? $upcomingDrives[0] : null;

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Life Saver Blood Bank</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(45deg, #c8eafc, #ffffff, #c8fcd4);
            background-size: 400% 400%;
            animation: gradientBG 40s ease infinite;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            overflow: hidden;
        }

        @keyframes gradientBG {
            0% {background-position: 0% 50%;}
            50% {background-position: 100% 50%;}
            100% {background-position: 0% 50%;}
        }

        .sidebar {
            width: 260px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(14px);
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            border-right: 2px solid rgba(255,255,255,0.3);
            box-shadow: 2px 0 8px rgba(0,0,0,0.1);
        }

        .sidebar h3 {
            margin: 12px 0 5px;
            font-size: 22px;
            font-weight: bold;
        }

        .sidebar .option {
            width: 100%;
            background: rgba(255, 255, 255, 0.25);
            padding: 12px 0;
            text-align: center;
            border-radius: 10px;
            text-decoration: none;
            color: #333;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .sidebar .option:hover {
            background: rgba(255, 255, 255, 0.45);
            transform: translateY(-3px);
            box-shadow: 0 5px 12px rgba(0,0,0,0.2);
        }

        .main-content {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow-y: auto;
            position: relative;
        }

        .main-content h1 {
            margin-bottom: 10px;
            font-size: 36px;
        }

        .welcome-message {
            background: rgba(255, 255, 255, 0.3);
            padding: 20px 30px;
            border-radius: 20px;
            font-size: 18px;
            margin-top: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 600px;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 50px;
            flex-wrap: wrap;
            max-width: 100%;
        }

        .button {
            background: rgba(255, 255, 255, 0.3);
            border: 2px solid white;
            border-radius: 15px;
            color: #333;
            font-size: 16px;
            font-weight: bold;
            display: flex;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            transition: transform 0.3s, background 0.3s;
            width: 150px;
            height: 100px;
            text-align: center;
            box-shadow: 0 6px 15px rgba(0,0,0,0.2);
            animation: pulse 2s infinite;
        }

        .button:hover {
            transform: scale(1.05);
            background: rgba(255, 255, 255, 0.4);
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .upcoming-container {
            margin-top: 60px;
            width: 100%;
            max-width: 800px;
            background: rgba(255,255,255,0.4);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .upcoming-container h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 26px;
        }

        .camp-item {
            background: rgba(255,255,255,0.7);
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .camp-item strong {
            color: #333;
        }

        .message {
            color: green;
            font-weight: bold;
            margin-top: 20px;
        }

        .error {
            color: red;
            font-weight: bold;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .button-container {
                flex-direction: column;
                align-items: center;
            }

            .button {
                width: 80%;
                max-width: 250px;
            }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h3><?php echo htmlspecialchars($username); ?></h3>
    <a href="user.php" class="option">👤 Profile</a>
    <a href="reward.php" class="option">🏆 Reward</a>
    <a href="page.php" class="option">🚪 Logout</a>
</div>

<div class="main-content">
    <h1>Life Saver Blood Bank</h1>

    <div class="welcome-message">
        Welcome, <strong><?php echo htmlspecialchars($username); ?></strong>! 🌟<br>
        Thank you for being a real-life hero.
    </div>

    <div class="button-container">
        <a href="need.php" class="button">🩸<br>Need Blood</a>
        <a href="donateoptions.php" class="button">❤️<br>Donate Blood</a>
        <a href="donor.php" class="button">👩‍⚕️<br>Find Donors</a>
        <a href="feedback.php" class="button">💬<br>Feedback</a>
    </div>

    <div class="upcoming-container">
        <h2>Upcoming Donation Drive</h2>
        <?php if ($latestDrive) { ?>
            <div class="camp-item">
                <strong>Drive Date:</strong> <?php echo htmlspecialchars($latestDrive['date']); ?><br>
                <strong>Time:</strong> <?php echo date('g:i A', strtotime($latestDrive['time'])); ?><br>
                <strong>Location:</strong> <?php echo htmlspecialchars($latestDrive['locality']) . ', ' . htmlspecialchars($latestDrive['city']) . ', ' . htmlspecialchars($latestDrive['state']); ?>
            </div>
        <?php } else { ?>
            <p>No upcoming drives at the moment.</p>
        <?php } ?>
    </div>

</div>
</body>
</html>
