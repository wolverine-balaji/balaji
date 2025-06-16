<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate Blood Options</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #ff6666, #cc0000);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
        }

        .container {
            text-align: center;
            padding: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            backdrop-filter: blur(12px);
        }

        h1 {
            font-size: 36px;
            margin-bottom: 30px;
        }

        .option-buttons {
            display: flex;
            gap: 30px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .option-button {
            background: rgba(255, 255, 255, 0.2);
            padding: 30px 40px;
            border-radius: 15px;
            text-decoration: none;
            color: white;
            font-size: 20px;
            font-weight: bold;
            transition: all 0.3s ease;
            width: 250px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .option-button:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-5px);
        }

        .emoji {
            font-size: 50px;
            display: block;
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .option-buttons {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Choose How You'd Like to Donate</h1>
        <div class="option-buttons">
            <a href="donate.php" class="option-button">
                <span class="emoji">🩸</span>
                Join a Blood Camp
            </a>
            <a href="book_appoint.php" class="option-button">
                <span class="emoji">🏥</span>
                Find Appointment in Hospital
            </a>
        </div>
    </div>
</body>
</html>
