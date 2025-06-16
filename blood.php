

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/split.css">
<script src="splash.js"></script>
    <title>Brutal Blood Login</title>
    <style>
        body {
            background: darkred;
            color: red;
            font-family: 'Courier New', monospace;
            text-align: center;
            overflow: hidden;
            position: relative;
        }
        .container {
            margin-top: 15%;
            animation: shake 0.5s infinite alternate;
            position: relative;
            z-index: 2;
        }
        input {
            background: black;
            color: red;
            border: 2px solid red;
            padding: 10px;
            font-size: 20px;
            text-align: center;
            outline: none;
        }
        input:focus {
            animation: glitch 1s infinite, blood-splash 0.2s forwards;
        }
        button {
            background: red;
            color: black;
            font-size: 20px;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        button:active::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: red;
            border-radius: 50%;
            opacity: 0.8;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            animation: blood-explode 0.3s forwards;
        }
        .blood-splash {
            position: absolute;
            width: 100vw;
            height: 100vh;
            background: url('https://media.giphy.com/media/l0ExdMHUDKteztyfe/giphy.gif');
            background-size: cover;
            opacity: 0;
            pointer-events: none;
            animation: blood-splash-effect 0.4s forwards;
        }
        @keyframes shake {
            from { transform: translateX(-5px); }
            to { transform: translateX(5px); }
        }
        @keyframes glitch {
            0% { text-shadow: -3px 3px 0 lime; }
            25% { text-shadow: 3px -3px 0 cyan; }
            50% { text-shadow: -3px -3px 0 yellow; }
            75% { text-shadow: 3px 3px 0 magenta; }
        }
        @keyframes blood-splash {
            0% { box-shadow: 0 0 0 red; }
            100% { box-shadow: 20px 20px 50px red; }
        }
        @keyframes blood-explode {
            0% { transform: translate(-50%, -50%) scale(0); opacity: 1; }
            50% { transform: translate(-50%, -50%) scale(1.5); opacity: 0.5; }
            100% { transform: translate(-50%, -50%) scale(2); opacity: 0; }
        }
        @keyframes blood-splash-effect {
            0% { opacity: 0; }
            50% { opacity: 1; }
            100% { opacity: 0; }
        }
    </style>
    <script>
        function triggerBloodSplash() {
            let splash = document.createElement("div");
            splash.className = "blood-splash";
            document.body.appendChild(splash);
            setTimeout(() => splash.remove(), 500);
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>ENTER THE BLOOD ZONE</h1>
        <form method="POST" onsubmit="triggerBloodSplash()">
            <input type="text" name="username" placeholder="Username" required><br><br>
            <input type="password" name="password" placeholder="Password" required><br><br>
            <button type="submit">SUBMIT YOUR FATE</button>
        </form>
        <?php if (isset($error)) echo "<p style='color:white;'>$error</p>"; ?>
    </div>
</body>
</html>
