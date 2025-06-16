<?php
// DB connection
$host = "localhost";
$user = "root";
$password = "";
$dbname = "login";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch blood stock data
$sql = "SELECT blood_type, COUNT(*) AS units_available FROM donor GROUP BY blood_type";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

$bloodGroups = [];
$units = [];
$lowStock = [];

while ($row = $result->fetch_assoc()) {
    $bloodGroups[] = $row['blood_type'];
    $units[] = $row['units_available'];

    if ((int)$row['units_available'] < 3) {
        $lowStock[] = $row['blood_type'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Blood Stock Dashboard</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center p-6 space-y-8">
  <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-4xl">
    <h2 class="text-2xl font-bold text-center text-red-600 mb-6">Blood Stock Bar Chart</h2>
    <canvas id="barChart" height="120"></canvas>
  </div>

  <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-4xl">
    <h2 class="text-2xl font-bold text-center text-emerald-600 mb-6">Blood Stock Distribution (Pie)</h2>
    <canvas id="pieChart" height="120"></canvas>
  </div>

  <?php if (!empty($lowStock)): ?>
    <div class="bg-white p-6 rounded-xl shadow-lg w-full max-w-2xl text-center">
      <h3 class="text-lg font-semibold text-red-700 mb-3">⚠️ Low Stock Alert</h3>
      <p class="text-sm text-gray-700">The following blood groups have low stock (less than 3 units):</p>
      <div class="mt-2 text-red-500 font-semibold">
        <?php echo implode(", ", $lowStock); ?>
      </div>
    </div>
  <?php endif; ?>

  <script>
    const bloodGroups = <?php echo json_encode($bloodGroups); ?>;
    const units = <?php echo json_encode($units); ?>;
    const colors = ['#ef4444', '#f97316', '#facc15', '#22c55e', '#3b82f6', '#8b5cf6', '#ec4899', '#14b8a6'];

    // Bar Chart
    new Chart(document.getElementById('barChart').getContext('2d'), {
      type: 'bar',
      data: {
        labels: bloodGroups,
        datasets: [{
          label: 'Units Available',
          data: units,
          backgroundColor: colors,
          borderRadius: 10
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
          title: {
            display: true,
            text: 'Blood Group Availability',
            font: { size: 18 }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: { precision: 0, color: '#1f2937' }
          },
          x: {
            ticks: { color: '#1f2937' }
          }
        }
      }
    });

    // Pie Chart
    new Chart(document.getElementById('pieChart').getContext('2d'), {
      type: 'pie',
      data: {
        labels: bloodGroups,
        datasets: [{
          label: 'Blood Group Share',
          data: units,
          backgroundColor: colors
        }]
      },
      options: {
        responsive: true,
        plugins: {
          title: {
            display: true,
            text: 'Blood Stock Percentage',
            font: { size: 18 }
          }
        }
      }
    });
  </script>
</body>
</html>
