<?php
session_start();

$conn = new mysqli("localhost", "root", "", "login");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$requestId = $_SESSION['request_id'] ?? null;
$message = "⚠️ Something went wrong. Please verify your request.";

$debugInfo = ""; // store debug information separately

if ($requestId) {
    $debugInfo .= "Request ID = " . $requestId . "<br>";

    $stmt = $conn->prepare("SELECT status FROM request WHERE id = ?");
    $stmt->bind_param("i", $requestId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $debugInfo .= "Found row in DB<br>";
        $status = $row['status'];
        $debugInfo .= "Status = " . $status . "<br>";

        switch ($status) {
            case 'pending':
                $message = "⏳ Your blood request is pending.";
                break;
            case 'accepted':
                $message = "✅ Your blood request is accepted.";
                break;
            case 'rejected':
                $message = "❌ Due to low stocks, your request is rejected.";
                break;
            default:
                $message = " status: " . htmlspecialchars($status);
                break;
        }
    } else {
        $debugInfo .= " No row found for this request ID<br>";
    }

    $stmt->close();
} else {
    $debugInfo .= "No request ID in session<br>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Status</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ff4d4d, #b30000);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: white;
        }
        .status-container {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            max-width: 500px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }
        .status-message {
            font-size: 1.8rem;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .debug-box {
            margin-top: 20px;
            background: rgba(0, 0, 0, 0.3);
            padding: 20px;
            border-radius: 10px;
            font-size: 0.9rem;
            text-align: left;
            overflow-x: auto;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            background: #dc2626;
            color: white;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: bold;
            transition: background 0.3s;
            text-decoration: none;
        }
        .back-btn:hover {
            background: #991b1b;
        }
    </style>
</head>
<body>

<div class="status-container">
    <div class="status-message">
        <?php echo $message; ?>
    </div>

    <!-- Back Button -->
    <a href="need.php" class="back-btn">⬅ Go Back to Request Page</a>

    <!-- Debug Info (remove later if not needed) -->
    <div class="debug-box">
        <?php echo $debugInfo; ?>
    </div>
</div>

</body>
</html>
