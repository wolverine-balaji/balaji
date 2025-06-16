<?php
// Directly establish the connection in this file

// Database configuration
$host = 'localhost';       // Database host
$dbname = 'login'; // Your database name
$username = 'root';         // Your database username
$password = '';             // Your database password (for local development, it is often an empty string)

// Create a connection using PDO (PHP Data Objects)
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception to easily catch and handle errors
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If the connection fails, it will display an error message
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Query for blood stock data
$query = "SELECT blood_group, SUM(units) as total_units FROM blood_stock GROUP BY blood_group";
$stmt = $pdo->prepare($query);
$stmt->execute();
$bloodStocks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blood Stock Graphs</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    </style>
</head>
<body>

<div class="container">
    <h2>Blood Stock Charts</h2>

    <!-- Pie Chart -->
    <canvas id="pieChart"></canvas>

    <!-- Bar Chart -->
    <canvas id="barChart"></canvas>

    <!-- Alert if units are below 3 -->
    <?php foreach ($bloodStocks as $stock): ?>
        <?php if ($stock['total_units'] < 3): ?>
            <div class="alert alert-danger mt-3">
                <strong>Alert:</strong> The blood group <?= htmlspecialchars($stock['blood_group']) ?> has less than 3 units available.
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

<script>
    // Pie Chart
    const pieData = {
        labels: <?php echo json_encode(array_column($bloodStocks, 'blood_group')); ?>,
        datasets: [{
            label: 'Total Units of Blood',
            data: <?php echo json_encode(array_column($bloodStocks, 'total_units')); ?>,
            backgroundColor: ['#ff9999', '#66b3ff', '#99ff99', '#ffcc99', '#c2c2f0', '#ffb3e6', '#ff6666', '#c2f0c2']
        }]
    };

    const pieConfig = {
        type: 'pie',
        data: pieData
    };

    const pieChart = new Chart(document.getElementById('pieChart'), pieConfig);

    // Bar Chart
    const barData = {
        labels: <?php echo json_encode(array_column($bloodStocks, 'blood_group')); ?>,
        datasets: [{
            label: 'Total Units of Blood',
            data: <?php echo json_encode(array_column($bloodStocks, 'total_units')); ?>,
            backgroundColor: '#66b3ff'
        }]
    };

    const barConfig = {
        type: 'bar',
        data: barData
    };

    const barChart = new Chart(document.getElementById('barChart'), barConfig);
</script>

</body>
</html>
