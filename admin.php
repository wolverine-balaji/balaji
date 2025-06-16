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

// Handle form submission for Accept/Reject actions
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['request_id']) && isset($_POST['action']) && isset($_POST['source'])) {
    $request_id = $_POST['request_id'];
    $action = $_POST['action'];
    $source = $_POST['source']; // 'user' or 'hospital'

    if (in_array($action, ['accepted', 'rejected'])) {
        $table = ($source === 'hospital') ? 'blood_requests' : 'request';

        $stmt = $conn->prepare("UPDATE $table SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $action, $request_id);
        $stmt->execute();
        $stmt->close();

        header("Location: admin.php?status=updated");
        exit();
    }
}

// Fetch total donors count
$result = $conn->query("SELECT COUNT(*) AS total FROM donor");
$row = $result->fetch_assoc();
$total_donors = $row['total'];

// Fetch user blood requests
$pendingRequests = $conn->query("SELECT * FROM request WHERE status = 'pending' ORDER BY requested_at DESC");

// Fetch hospital blood requests
$hospitalRequests = $conn->query("SELECT * FROM blood_requests WHERE status = 'pending' ORDER BY requested_at DESC");

if (!$pendingRequests || !$hospitalRequests) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {


      font-family: 'Poppins', sans-serif;
      background-image: url('Blood-Donation-2.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }

    .glass-card {
      background-color: rgba(220, 38, 38, 0.8);
      backdrop-filter: blur(8px);
      transition: all 0.3s ease;
      width: 260px;
      padding: 16px;
      border-radius: 1rem;
      text-align: center;
      color: white;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

   .glass-card:hover {
  transform: translateY(-5px) scale(1.03);
  background-color: rgba(185, 28, 28, 0.85);
  box-shadow: 0 12px 32px rgba(0, 0, 0, 0.25), 0 0 15px rgba(255, 255, 255, 0.2);
}


    .dashboard-container {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 30px;
      max-width: 1200px;
      margin: auto;
      padding-top: 40px;
    }

    .card-icon {
      font-size: 2rem;
      margin-bottom: 10px;
    }

    .card-title {
      font-size: 1.125rem;
      font-weight: 600;
    }

    .pending-section {
      max-width: 900px;
      margin: 60px auto 40px;
    }

    .hospital-requests-section {
      max-width: 900px;
      margin: 60px auto 40px;
      background-color: rgba(255, 255, 255, 0.9);
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    }

    .welcome-banner {
      background-color: rgba(255, 255, 255, 0.85);
      margin: 20px auto;
      padding: 24px;
      max-width: 900px;
      text-align: center;
      border-radius: 16px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    }

    .welcome-banner h1 {
      font-size: 2rem;
      color: #b91c1c;
      font-weight: 700;
    }

    .welcome-banner p {
      font-size: 1.1rem;
      color: #4b4b4b;
      margin-top: 10px;
      font-style: italic;
    }
  </style>
</head>
<body class="bg-white bg-opacity-80 min-h-screen pb-10">
<!-- Top Navigation Bar -->
<nav class="w-full bg-red-700 text-white p-4 flex justify-between items-center shadow-lg">
  <div class="text-xl font-semibold">Life Saver Blood Bank Admin Panel</div>
  <a href="page.php" class="bg-white text-red-700 px-4 py-2 rounded-lg font-bold hover:bg-gray-200 transition">
    Logout
  </a>
</nav>

  <!-- Welcome Message -->
  <div class="welcome-banner">
    <h1>👋 Welcome, Admin</h1>
    <p>"You don't just manage blood—you manage hope"</p>
  </div>

  <!-- Dashboard Cards -->
  <div class="dashboard-container">
    <a href="hospital.php" class="glass-card">
      <div class="card-icon">🏥</div>
      <div class="card-title">Total Registered Hospitals</div>
    </a>

    <a href="stock.php" class="glass-card">
      <div class="card-icon">🧪</div>
      <div class="card-title">View Blood Stocks</div>
    </a>

    <a href="view.php" class="glass-card">
      <div class="card-icon">🩸</div>
      <div class="card-title">Total Donors</div>
    </a>

    <a href="drive.php" class="glass-card">
      <div class="card-icon">📅</div>
      <div class="card-title">Upcoming Blood Donations</div>
    </a>

 
  </div>

  <!-- Pending Blood Requests (Users) -->
  <div class="pending-section bg-white bg-opacity-90 p-8 rounded-xl shadow-2xl">
    <h2 class="text-3xl font-bold text-red-700 mb-6 text-center">🩸 Pending Blood Requests</h2>

    <?php if ($pendingRequests->num_rows > 0): ?>
      <?php while ($req = $pendingRequests->fetch_assoc()): ?>
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-red-100 p-5 mb-4 rounded-lg shadow-md gap-4">
          <div class="text-red-900">
            <p><strong>State:</strong> <?= $req['state'] ?></p>
            <p><strong>District:</strong> <?= $req['district'] ?></p>
            <p><strong>Blood Type:</strong> <?= $req['blood_type'] ?></p>
            <p><strong>Requested At:</strong> <?= date("d M Y, h:i A", strtotime($req['requested_at'])) ?></p>
          </div>
          <form method="POST" class="flex gap-3">
            <input type="hidden" name="request_id" value="<?= $req['id'] ?>">
            <input type="hidden" name="source" value="user">
            <button type="submit" name="action" value="accepted" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-md">
              Accept
            </button>
            <button type="submit" name="action" value="rejected" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md">
              Reject
            </button>
          </form>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-gray-700 text-center text-lg">No pending blood requests at the moment.</p>
    <?php endif; ?>
  </div>

  <!-- Hospital-specific Requests -->
  <div class="hospital-requests-section">
    <h2 class="text-3xl font-bold text-red-700 mb-6 text-center">🏥 Pending Requests from Hospitals</h2>

    <?php if ($hospitalRequests->num_rows > 0): ?>
      <?php while ($req = $hospitalRequests->fetch_assoc()): ?>
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-yellow-100 p-5 mb-4 rounded-lg shadow-md gap-4">
          <div class="text-yellow-900">
            <p><strong>Hospital:</strong> <?= $req['hospital_name'] ?></p>
            <p><strong>Blood Group:</strong> <?= $req['blood_group'] ?></p>
            <p><strong>Requested At:</strong> <?= date("d M Y, h:i A", strtotime($req['requested_at'])) ?></p>
          </div>
          <form method="POST" class="flex gap-3">
            <input type="hidden" name="request_id" value="<?= $req['id'] ?>">
            <input type="hidden" name="source" value="hospital">
            <button type="submit" name="action" value="accepted" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-md">
              Accept
            </button>
            <button type="submit" name="action" value="rejected" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md">
              Reject
            </button>
          </form>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-gray-700 text-center text-lg">No pending requests from hospitals.</p>
    <?php endif; ?>
  </div>
<footer class="text-center mt-12 p-6 text-white bg-red-800 bg-opacity-90">
  <p class="text-sm">&copy; 2025 LifeSaver Blood Bank. All rights reserved.</p>
</footer>


</body>
</html>

<?php $conn->close(); ?>
