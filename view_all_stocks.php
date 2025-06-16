<?php
session_start(); // Start the session to use session variables

// Simulated login session for testing (REMOVE THIS in production)
if (!isset($_SESSION['hospital_name'])) {
    $_SESSION['hospital_name'] = 'CityCare Hospital'; // Replace with actual session logic
}

$hospitalName = $_SESSION['hospital_name'];

// Database configuration
$host = 'localhost';
$dbname = 'login';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Handle insert new blood stock
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['blood_group_insert'], $_POST['units'])) {
    $bloodGroupInsert = $_POST['blood_group_insert'];
    $units = $_POST['units'];

    $insertQuery = "INSERT INTO blood_stock (hospital_name, blood_group, units) 
                    VALUES (:hospital_name, :blood_group, :units)";
    $stmt = $pdo->prepare($insertQuery);
    $stmt->bindParam(':hospital_name', $hospitalName);
    $stmt->bindParam(':blood_group', $bloodGroupInsert);
    $stmt->bindParam(':units', $units);

    if ($stmt->execute()) {
        header("Location: blood_stock_graph.php");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Failed to add blood stock.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Blood Stock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: "Poppins", sans-serif;
            margin: 0;
            background-color: #f0f0f0;
        }
        .container {
            padding: 30px;
        }
        .btn-submit {
            background: linear-gradient(to right, #ff5e62, #ff9966);
            color: white;
            padding: 12px 20px;
            font-size: 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
        }
        .btn-submit:hover {
            background: linear-gradient(to right, #ff3e3e, #ff7e67);
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add Blood Stock</h2>

    <form method="POST">
        <div class="mb-3">
            <label for="hospital_name" class="form-label">Hospital Name</label>
            <input type="text" class="form-control" id="hospital_name" name="hospital_name"
                   value="<?= htmlspecialchars($hospitalName) ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="blood_group_insert" class="form-label">Blood Group</label>
            <select class="form-select" id="blood_group_insert" name="blood_group_insert" required>
                <option value="" disabled selected>Select Blood Group</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="units" class="form-label">Units</label>
            <input type="number" class="form-control" id="units" name="units" required min="1">
        </div>
        <button type="submit" class="btn-submit">Add Blood Stock</button>
    </form>
</div>

</body>
</html>
