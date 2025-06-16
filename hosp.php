<?php
session_start();

// Example session variable setup
$hospitalName = isset($_SESSION['hospital_name']) ? $_SESSION['hospital_name'] : 'Hospital Partner';

// Success message after appointment
$appointmentMessage = isset($_SESSION['appointment_success_message']) ? $_SESSION['appointment_success_message'] : null;

// Dismiss message handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dismiss_message'])) {
    $_SESSION['appointment_message_dismissed'] = true;
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hospital Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: "Poppins", sans-serif;
            margin: 0;
            color: #fff;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: linear-gradient(to bottom right, rgba(0, 0, 0, 0.6), rgba(70, 70, 70, 0.6)), url('hosp.jpg');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center 30%;
            background-attachment: fixed;
        }

        header {
            position: relative;
            background: rgba(0, 0, 0, 0.7);
            color: #fff;
            padding: 30px 20px;
            text-align: center;
            font-size: 2.2rem;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .logout-btn form {
            display: inline;
        }

        .logout-btn button {
            background-color: #dc3545;
            border: none;
            padding: 8px 16px;
            color: white;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            font-weight: 600;
        }

        .logout-btn button:hover {
            background-color: #c82333;
        }

        .welcome-section {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            width: 90%;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }

        .welcome-section h2 {
            font-size: 28px;
            color: #ffcccb;
        }

        .welcome-section p {
            font-size: 18px;
            color: #ffe0e0;
        }

        .container {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
            width: 100%;
            max-width: 900px;
        }

        .btn-box {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 30px 20px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .feature-btn {
            background: linear-gradient(to right, #ff5e62, #ff9966);
            color: white;
            padding: 15px 20px;
            font-size: 16px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            width: 100%;
            font-weight: 600;
        }

        .feature-btn:hover {
            background: linear-gradient(to right, #ff3e3e, #ff7e67);
        }

        footer {
            background: rgba(0, 0, 0, 0.8);
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 14px;
            margin-top: auto;
        }
    </style>
</head>
<body>

<header>
    Hospital Dashboard – Blood Bank
    <div class="logout-btn">
        <form action="2.php" method="post">
            <button type="submit">Logout</button>
        </form>
    </div>
</header>

<div class="welcome-section">
    <h2>Welcome, <?= htmlspecialchars($hospitalName) ?>!</h2>
    <p>"Saving lives starts with you. Thank you for being the bridge between donors and those in need."</p>
</div>

<?php if ($appointmentMessage && !isset($_SESSION['appointment_message_dismissed'])): ?>
    <div class="alert alert-success text-center" style="max-width: 600px; margin: 20px auto; padding: 15px; border-radius: 8px;">
        <?= htmlspecialchars($appointmentMessage) ?>
        <form method="post" style="display: inline;">
            <button type="submit" name="dismiss_message" class="btn btn-danger btn-sm ms-3">Dismiss</button>
        </form>
    </div>
<?php endif; ?>

<div class="container">
    <div class="dashboard-grid">
        <!-- Request Blood -->
        <div class="btn-box">
            <form action="your_request.php">
                <button class="feature-btn" type="submit">Request Blood from Admin</button>
            </form>
        </div>

        <!-- View All Stocks -->
        <div class="btn-box">
            <form action="view_all_stocks.php">
                <button class="feature-btn" type="submit">Add Blood Stocks</button>
            </form>
        </div>

        <!-- Appointment Dates -->
        <div class="btn-box">
            <form action="appoint.php">
                <button class="feature-btn" type="submit">Upcoming Appointment Dates</button>
            </form>
        </div>
    </div>
</div>

<footer>
    &copy; 2025 Life Saver Blood Bank. All rights reserved.
</footer>

</body>
</html>
