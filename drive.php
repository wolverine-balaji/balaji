<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "login"; // Change if your DB name is different

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $state = $_POST['state'] ?? '';
    $city = $_POST['city'] ?? '';
    $locality = $_POST['locality'] ?? '';

    // Insert into donation_drives table
    $stmt = $conn->prepare("INSERT INTO donation_drives (date, time, state, city, locality) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $date, $time, $state, $city, $locality);

    if ($stmt->execute()) {
        echo "<script>alert('Blood Drive Scheduled Successfully'); window.location.href='drive.php';</script>";
    } else {
        echo "<script>alert('Error: Could not save the data.');</script>";
    }

    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Schedule Blood Drive</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-red-100 font-sans">
  <div class="w-full max-w-md">
    <form method="POST" class="bg-white p-8 rounded-xl shadow-lg space-y-5">
      <h2 class="text-2xl font-bold text-red-600 text-center">📅 Schedule Blood Donation</h2>

      <div>
        <label class="block font-semibold text-red-700 mb-1">Select Date</label>
        <input type="date" name="date" required class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-400">
      </div>

      <div>
        <label class="block font-semibold text-red-700 mb-1">Select Time (24-hour format)</label>
        <select name="time" required class="w-full border border-gray-300 rounded px-4 py-2">
          <option value="">Select Time</option>
          <?php
          for ($hour = 9; $hour <= 16; $hour++) {
$formatted = sprintf("%02d:00:00", $hour);

              echo "<option value='$formatted'>$formatted</option>";
          }
          ?>
        </select>
      </div>

      <div>
        <label class="block font-semibold text-red-700 mb-1">State</label>
        <select name="state" required class="w-full border border-gray-300 rounded px-4 py-2">
          <option value="">Select State</option>
          <option value="Tamil Nadu">Tamil Nadu</option>
          <option value="Karnataka">Karnataka</option>
          <option value="Kerala">Kerala</option>
        </select>
      </div>

      <div>
        <label class="block font-semibold text-red-700 mb-1">City</label>
        <select name="city" required class="w-full border border-gray-300 rounded px-4 py-2">
          <option value="">Select City</option>
          <option value="Chennai">Chennai</option>
          <option value="Bangalore">Bangalore</option>
          <option value="Kochi">Kochi</option>
          <option value="Coimbatore">Coimbatore</option>
          <option value="Mysore">Mysore</option>
          <option value="Thrissur">Thrissur</option>
        </select>
      </div>

      <div>
        <label class="block font-semibold text-red-700 mb-1">Locality</label>
        <input type="text" name="locality" required placeholder="e.g., Anna Nagar, MG Road..." class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-400">
      </div>

      <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 w-full rounded-lg">
        Submit
      </button>

      <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div class="bg-green-100 text-green-800 px-4 py-3 mt-4 rounded text-center">
          ✅ Drive scheduled on <strong><?= htmlspecialchars($date) ?></strong> at <strong><?= htmlspecialchars($time) ?></strong><br>
          Location: <strong><?= htmlspecialchars($locality) ?>, <?= htmlspecialchars($city) ?>, <?= htmlspecialchars($state) ?></strong>
        </div>
      <?php endif; ?>
    </form>
  </div>
</body>
</html>
