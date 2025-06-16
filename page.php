<?php
session_start();

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "login";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle POST login request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // ✅ Admin Login
    if ($username === "blood" && $password === "bank") {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['role'] = 'admin';
        header("Location: admin.php");
        exit();
    }

    // ✅ Hospital Login
    if (substr($username, 0, 4) === "hosp") {
        $stmt = $conn->prepare("SELECT * FROM hospital WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $hospital = $result->fetch_assoc();
            if ($password === $hospital['password']) {
                $_SESSION['hospital_logged_in'] = true;
                $_SESSION['role'] = 'hospital';
                $_SESSION['username'] = $hospital['username'];
                $_SESSION['hospital_name'] = $hospital['hospital_name'];
                header("Location: hosp.php");
                exit();
            } else {
                echo "<script>alert('Invalid password for hospital account'); window.location.href='login.php';</script>";
                exit();
            }
        } else {
            echo "<script>alert('Hospital account not found'); window.location.href='login.php';</script>";
            exit();
        }
    }

    // ✅ Normal User Login
    $stmt = $conn->prepare("SELECT * FROM receipient WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['photo'] = $user['photo'];
            $_SESSION['role'] = 'user';
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid password'); window.location.href='login.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Username not found'); window.location.href='login.php';</script>";
        exit();
    }
}

$conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Bank Login</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ff4d4d, #b30000);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Center the login box and align the image to the left */
        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 40px; /* Adds spacing between image and login box */
            width: 80%;
            max-width: 900px;
        }

        .image-container {
            flex-shrink: 0;
        }

        .image-container img {
            width: 350px;
            height: auto;
            border-radius: 20px;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.15);
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            color: white;
            width: 350px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            animation: fadeIn 1.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            margin-bottom: 10px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #ff1e1e;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
        }

        button:hover {
            background: #cc0000;
        }

        /* Background bubbles */
        .bubble {
            position: absolute;
            bottom: -50px;
            width: 20px;
            height: 20px;
            background: rgba(255, 102, 102, 0.7);
            border-radius: 50%;
            animation: floatBubble infinite linear;
        }

        @keyframes floatBubble {
            from { transform: translateY(0) scale(1); opacity: 1; }
            to { transform: translateY(-100vh) scale(1.2); opacity: 1; }
        }
    </style>
</head>
<body>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            createBubbles(30);
        });

        function createBubbles(count) {
            for (let i = 0; i < count; i++) {
                let bubble = document.createElement("div");
                bubble.classList.add("bubble");
                document.body.appendChild(bubble);
                bubble.style.left = Math.random() * 100 + "vw";
                bubble.style.width = bubble.style.height = Math.random() * 25 + 10 + "px";
                bubble.style.animationDuration = Math.random() * 8 + 5 + "s";
            }
        }
    </script>

    <div class="container">
        <!-- Image placed left -->
        <div class="image-container">
            <img src="DONATE BLOOD.jpg" alt="World Blood Donor Day">
        </div>
        
        <!-- Login box placed at center -->
        <div class="login-container">
            <h2>🩸Life Saver Blood Bank🩸</h2>
            <p class="tagline">❤️ Saving Lives, One Drop at a Time ❤️</p>
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
                <p>New User? <a href="receipient.php" style="color: white; text-decoration: underline;">Sign Up</a></p>
            </form>
        </div>
    </div>
</body>
</html>  