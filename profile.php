<?php
    session_start();
    $username = $_SESSION['username'] ?? '';

    // Database connection
    $conn = new mysqli("localhost", "root", "", "login");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $user = null;
    if ($username !== '') {
        $stmt = $conn->prepare("SELECT * FROM donors WHERE name = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
    }
    $conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #ff4d4d, #b30000);
            color: white;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .profile-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 20px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }

        .profile-card h2 {
            margin-top: 0;
            text-align: center;
        }

        .profile-card .info {
            margin: 10px 0;
            padding: 10px;
            background: rgba(255,255,255,0.15);
            border-radius: 10px;
        }

        .profile-card .info strong {
            display: inline-block;
            width: 120px;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: rgba(255,255,255,0.2);
            border: 2px solid white;
            border-radius: 10px;
            color: white;
            font-weight: bold;
            text-decoration: none;
            transition: background 0.3s;
            text-align: center;
        }

        .back-btn:hover {
            background: rgba(255,255,255,0.35);
        }
    </style>
</head>
<body>
    <div class="profile-card">
        <h2>👤 Your Profile</h2>
        <?php if ($user): ?>
            <div class="info"><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></div>
            <div class="info"><strong>DOB:</strong> <?php echo htmlspecialchars($user['dob']); ?></div>
            <div class="info"><strong>Gender:</strong> <?php echo htmlspecialchars($user['gender']); ?></div>
            <div class="info"><strong>Blood Group:</strong> <?php echo htmlspecialchars($user['blood_group']); ?></div>
            <div class="info"><strong>Contact:</strong> <?php echo htmlspecialchars($user['contact']); ?></div>
            <div class="info"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></div>
            <div class="info"><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></div>
            <div class="info"><strong>City:</strong> <?php echo htmlspecialchars($user['city']); ?></div>
            <div class="info"><strong>State:</strong> <?php echo htmlspecialchars($user['state']); ?></div>
            <div class="info"><strong>Joined:</strong> <?php echo htmlspecialchars($user['created_at']); ?></div>
        <?php else: ?>
            <p>User details not found.</p>
        <?php endif; ?>
        <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
    </div>
</body>
</html>
