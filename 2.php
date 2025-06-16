<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Fetch feedback from the database
$feedbacks = [];
$conn = new mysqli("localhost", "root", "", "login");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT username, feedback FROM feedback ORDER BY created_at DESC LIMIT 6");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $feedbacks[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Life Saver Blood Bank</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #b10d2f;
            color: white;
            padding: 10px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .auth-buttons {
            display: flex;
            gap: 12px;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 6px 12px;
            border-radius: 8px;
            backdrop-filter: blur(4px);
        }

        .auth-buttons .btn {
            border: 2px solid white;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            font-weight: 600;
            padding: 8px 14px;
            border-radius: 6px;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .auth-buttons .btn:hover {
            background-color: white;
            color: #b10d2f;
        }

        #hero {
            margin-top: 90px;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            color: white;
            text-align: center;
        }

        #hero-video {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            object-fit: cover;
            z-index: -2;
        }

        .hero-overlay {
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: -1;
        }

        .hero-content h2 {
            font-size: 40px;
            margin-bottom: 20px;
        }

        .btn {
            background-color: #b10d2f;
            color: white;
            padding: 10px 18px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            transition: background 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }

        .btn:hover {
            background-color: #7a0b23;
            color: #fff;
            cursor: pointer;
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 40px 20px;
        }

        .social-icons a {
            margin: 0 10px;
            color: white;
            font-size: 20px;
        }

        .social-icons a:hover {
            color: #b10d2f;
        }
    </style>
</head>
<body>

<header>
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 40px; width: 100%;">
        <div style="display: flex; align-items: center; gap: 20px;">
            <div style="display: flex; align-items: center;">
                <img src="logo.png" alt="Blood Drop Logo" style="height: 60px; margin-right: 10px;" />
                <span style="color: white; font-size: 22px; font-weight: bold;">Life Saver Blood Bank</span>
            </div>
            <a href="about.php" style="color: white; text-decoration: none; font-size: 16px;">About Us</a>
            <a href="contact.php" style="color: white; text-decoration: none; font-size: 16px;">Contact</a>
        </div>

        <div class="auth-buttons" style="margin-left: auto;">
            <?php if (isset($_SESSION['user'])): ?>
                <a href="logout.php" class="btn">Logout</a>
            <?php else: ?>
                <a href="page.php" class="btn" id="loginBtn">
                    <span class="btn-text">Login</span>
                    <i class="fas fa-spinner fa-spin" id="spinner" style="display: none; margin-left: 8px;"></i>
                </a>
                <a href="receipient.php" class="btn">Register</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<section id="hero">
    <video autoplay muted loop id="hero-video">
        <source src="bloodv (1).mp4" type="video/mp4">
    </video>
    <div class="hero-overlay"></div>
    <div class="hero-content" style="display: flex; flex-direction: column; align-items: center; margin-top: 80px;">
        <h2 id="dynamic-heading" style="margin-bottom: 70px; margin-top: 100px;">Be Someone's Lifeline</h2> 
        <div class="cta-buttons" style="margin-left: 30px;">
        <p style="margin-left: 30px; background-color: #b10d2f; color: white; padding: 10px 18px; font-size: 16px; border-radius: 6px; display: inline-block; font-weight: bold;">
    Donate Now
</p>


        </div>
    </div>
</section>

<section style="background-color:#fff; padding: 50px 20px; text-align: center;">
    <h2 style="color:#b10d2f; margin-bottom: 20px;">❤️ Why Donate Blood?</h2>
    <p style="max-width: 700px; margin: 0 auto; color: #555;">
        Donating blood is a simple act that saves lives. Your one unit of blood can help up to three patients. It’s safe, quick, and greatly needed. Be a hero. Be a donor.
    </p>
<section style="background-color: #fff; padding: 60px 20px;">
    <h2 style="text-align: center; color: #b10d2f; font-size: 28px; margin-bottom: 40px;">🩸 Features of Blood Donation</h2>
    <div style="display: flex; justify-content: center; flex-wrap: wrap; gap: 30px; max-width: 1200px; margin: auto;">
        <div style="background-color: #f4f4f4; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 300px;">
            <i class="fas fa-heartbeat" style="color: #b10d2f; font-size: 30px; margin-bottom: 10px;"></i>
            <h3 style="color: #b10d2f; margin-bottom: 10px;">Saves Lives</h3>
            <p style="color: #444;">A single blood donation can help up to three people in need of urgent care or surgery.</p>
        </div>
        <div style="background-color: #f4f4f4; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 300px;">
            <i class="fas fa-hand-holding-medical" style="color: #b10d2f; font-size: 30px; margin-bottom: 10px;"></i>
            <h3 style="color: #b10d2f; margin-bottom: 10px;">Boosts Health</h3>
            <p style="color: #444;">Regular donation can improve heart health and stimulate new blood cell production.</p>
        </div>
        <div style="background-color: #f4f4f4; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 300px;">
            <i class="fas fa-users" style="color: #b10d2f; font-size: 30px; margin-bottom: 10px;"></i>
            <h3 style="color: #b10d2f; margin-bottom: 10px;">Community Impact</h3>
            <p style="color: #444;">It strengthens your bond with the community and inspires others to do the same.</p>
        </div>
    </div>
</section>

  </div>
</section>



<section style="background:#fff; padding: 50px 20px; text-align: center;">
    <h2 style="color:#b10d2f; margin-bottom: 20px;">💬 What People Say</h2>
    <div style="display: flex; justify-content: center; gap: 30px; flex-wrap: wrap; max-width: 1000px; margin: auto;">
        <?php if (!empty($feedbacks)): ?>
            <?php foreach ($feedbacks as $fb): ?>
                <div style="background:#f8f8f8; padding:20px; border-radius:10px; width:300px;">
                    <p>“<?= htmlspecialchars($fb['feedback']) ?>”</p>
                    <strong>- <?= htmlspecialchars($fb['username']) ?></strong>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No feedback available yet. Be the first to share your thoughts!</p>
        <?php endif; ?>
    </div>
</section>

<footer>
    <div class="social-icons">
        <a href="#"><i class="fab fa-facebook-f"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-linkedin-in"></i></a>
    </div>
    <p>&copy; 2025 LifeSaver Blood Bank. All Rights Reserved.</p>
</footer>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const headingText = "Be Someone's Lifeline";
    let i = 0;
    const speed = 100;
    const heading = document.getElementById("dynamic-heading");

    heading.innerHTML = "";

    function typeHeading() {
        if (i < headingText.length) {
            heading.innerHTML += headingText.charAt(i);
            i++;
            setTimeout(typeHeading, speed);
        }
    }

    typeHeading();

    // Spinner logic
    const loginBtn = document.getElementById("loginBtn");
    if (loginBtn) {
        let clicked = false;
        loginBtn.addEventListener("click", function (e) {
            if (clicked) return;
            clicked = true;
            e.preventDefault();
            document.query
