<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "login";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get filter values
$state = $_GET['state'] ?? '';
$district = $_GET['district'] ?? '';
$blood_type = $_GET['blood_type'] ?? '';

// Correct query using your DB column names
$query = "SELECT MIN(created_at) as created_at, name, contact_number, email, state, city, blood_type 
          FROM donor
          WHERE state = ? AND city = ? AND blood_type = ?
          GROUP BY email
          ORDER BY created_at DESC";


$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Query Prepare Failed: " . $conn->error); // Shows exact SQL error
}

$stmt->bind_param("sss", $state, $district, $blood_type);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donor Results</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #ff4d4d, #b30000);
            margin: 0;
            padding: 20px;
            color: white;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .donor-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            padding: 20px;
            border-radius: 15px;
            width: 280px;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.4);
        }

        .card h3 {
            margin: 0 0 10px;
            font-size: 20px;
        }

        .card p {
            margin: 5px 0;
        }

        .back-button {
            display: block;
            margin: 30px auto 0;
            text-align: center;
            padding: 10px 20px;
            background-color: #ffffff;
            color: #b30000;
            border-radius: 8px;
            font-weight: bold;
            text-decoration: none;
            transition: background 0.3s;
            width: fit-content;
        }

        .back-button:hover {
            background-color: #ffe0e0;
        }
    </style>
</head>
<body>
    <h2>Available Donors in <?php echo htmlspecialchars($district); ?>, <?php echo htmlspecialchars($state); ?> (<?php echo htmlspecialchars($blood_type); ?>)</h2>

    <div class="donor-list">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="card">
                    <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($row['contact_number']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                    <p><strong>Blood Type:</strong> <?php echo htmlspecialchars($row['blood_type']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($row['city']) . ', ' . htmlspecialchars($row['state']); ?></p>
                    <p><strong>Joined:</strong> <?php echo date("d M Y", strtotime($row['created_at'])); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center; font-size:18px;">No donors found for the selected filters.</p>
        <?php endif; ?>
    </div>

    <a href="donor.php" class="back-button">← Back to Search</a>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
