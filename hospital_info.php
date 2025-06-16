<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Partnered Hospitals</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f2f6fc;
            color: #333;
            margin: 0;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            padding: 30px 40px;
            max-width: 600px;
            width: 100%;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            text-align: center;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            background: #eaf4ff;
            padding: 12px 20px;
            margin-bottom: 10px;
            border-radius: 8px;
            transition: background 0.3s;
        }

        li:hover {
            background: #d0e9ff;
        }

        .no-results {
            color: #888;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $state = $_POST['state'];
    $city = $_POST['city'];

    // Connect to database
    $conn = new mysqli("localhost", "root", "", "login");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute query (fetch hospital_name and address)
    $stmt = $conn->prepare("SELECT hospital_name, address FROM hospital WHERE city = ? AND state = ?");
    $stmt->bind_param("ss", $city, $state);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<h2>Partnered Hospitals in " . htmlspecialchars($city) . ", " . htmlspecialchars($state) . "</h2>";
    if ($result->num_rows > 0) {
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li><strong>" . htmlspecialchars($row['hospital_name']) . "</strong><br><small>" . nl2br(htmlspecialchars($row['address'])) . "</small></li>";
        }
        echo "</ul>";
    } else {
        echo "<div class='no-results'>No partnered hospitals found in this location.</div>";
    }

    $stmt->close();
    $conn->close();
}
?>
</div>

</body>
</html>
