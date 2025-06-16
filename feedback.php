<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: page.php");
    exit();
}

$feedbackSaved = false;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $feedback = trim($_POST['feedback']);

    if (!empty($feedback)) {
        $conn = new mysqli("localhost", "root", "", "login");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("INSERT INTO feedback (user_id, username, feedback, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iss", $user_id, $username, $feedback);
        $stmt->execute();
        $stmt->close();
        $conn->close();

        $feedbackSaved = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Feedback - Lifesaver Blood Bank</title>
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #ffe0e0, #ffe6f0);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .feedback-box {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            max-width: 600px;
            width: 100%;
        }

        h2 {
            text-align: center;
            color: #b30000;
            margin-bottom: 30px;
        }

        textarea {
            width: 100%;
            height: 150px;
            border-radius: 10px;
            border: 1px solid #ccc;
            padding: 15px;
            font-size: 16px;
            resize: none;
        }

        button {
            margin-top: 20px;
            background-color: #ff4d4d;
            border: none;
            color: white;
            padding: 12px 25px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            display: block;
            width: 100%;
        }

        button:hover {
            background-color: #cc0000;
        }

        .message {
            background-color: #e6ffee;
            color: #006633;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="feedback-box">
    <h2>We Value Your Feedback ❤️</h2>

    <?php if ($feedbackSaved): ?>
        <div class="message">
            Thank you for your feedback, <?php echo htmlspecialchars($_SESSION['username']); ?>!
        </div>
    <?php else: ?>
        <form method="POST">
            <textarea name="feedback" placeholder="Share your experience or suggestions..." required></textarea>
            <button type="submit">Submit Feedback</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
