<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Bank Login</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');
        
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ff1e1e, #660000);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            position: relative;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.2);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            text-align: center;
            color: white;
            width: 400px;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            animation: fadeIn 1.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            font-size: 28px;
            margin-bottom: 15px;
        }

        .tagline {
            font-size: 16px;
            margin-bottom: 20px;
        }

        input {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            text-align: center;
            background: rgba(255, 255, 255, 0.8);
        }

        button {
            width: 100%;
            padding: 12px;
            background: #ff4d4d;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            transition: 0.3s;
        }

        button:hover {
            background: #cc0000;
        }

        .bubble {
            position: absolute;
            bottom: -50px;
            width: 20px;
            height: 20px;
            background: rgba(255, 102, 102, 0.7);
            border-radius: 50%;
            animation: floatBubble linear infinite;
        }

        @keyframes floatBubble {
            from { transform: translateY(0) scale(1); opacity: 1; }
            to { transform: translateY(-100vh) scale(1.2); opacity: 0; }
        }
    </style>
</head>
<body>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            createBubbles(40);
        });

        function createBubbles(count) {
            for (let i = 0; i < count; i++) {
                let bubble = document.createElement("div");
                bubble.classList.add("bubble");
                document.body.appendChild(bubble);
                bubble.style.left = Math.random() * 100 + "vw";
                bubble.style.width = bubble.style.height = Math.random() * 30 + 15 + "px";
                bubble.style.animationDuration = Math.random() * 10 + 6 + "s";
                setTimeout(() => { bubble.remove(); }, 18000);
            }
        }
    </script>
    
    <div class="login-container">
        <h2>🩸 Blood Bank Login 🩸</h2>
        <p class="tagline">❤️ Saving Lives, One Drop at a Time ❤️</p>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <p>Don't have an account? <a href="register.php" style="color: white; text-decoration: underline; font-weight: bold;">Sign Up</a></p>
        </form>
    </div>
</body>
</html>