<?php
session_start();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $hospitalName = isset($_SESSION['hospital_name']) ? $_SESSION['hospital_name'] : 'Hospital Partner';
    $blood_group = $_POST['blood_group'];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "login");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert into blood_requests table
    $stmt = $conn->prepare("INSERT INTO blood_requests (hospital_name, blood_group, status) VALUES (?, ?, 'pending')");
    $stmt->bind_param("ss", $hospitalName, $blood_group);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Your blood request has been successfully submitted!";
        header("Location: your_request.php"); // Redirect to the same page or a different page
        exit();
    } else {
        $_SESSION['message'] = "There was an error while submitting your request.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Blood Request</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #f87171, #ef4444);
      min-height: 100vh;
    }
  </style>
</head>
<body class="flex items-center justify-center py-16 px-4">

  <div class="bg-white bg-opacity-95 max-w-xl w-full p-10 rounded-2xl shadow-2xl">
    <h2 class="text-3xl font-bold text-center text-red-700 mb-6">🩸 Blood Request Form</h2>

    <?php if (isset($_SESSION['message'])): ?>
      <div class="bg-green-100 text-green-800 p-3 rounded-lg mb-4 text-center font-semibold shadow">
        <?= $_SESSION['message']; unset($_SESSION['message']); ?>
      </div>
    <?php endif; ?>

    <form action="your_request.php" method="POST" class="space-y-6">
      <!-- Hospital Name Display -->
      <div>
        <label class="block text-lg font-medium text-gray-700 mb-2">🏥 Hospital:</label>
        <input type="text" name="hospital" value="<?= htmlspecialchars($hospitalName); ?>" readonly
               class="w-full p-3 border border-gray-300 rounded-lg bg-gray-100 shadow-sm text-gray-600 font-semibold" />
      </div>

      <!-- Blood Group Dropdown -->
      <div>
        <label for="blood_group" class="block text-lg font-medium text-gray-700 mb-2">🧪 Select Blood Group:</label>
        <select name="blood_group" id="blood_group" required class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500">
          <option value="">-- Select Blood Group --</option>
          <?php foreach ($blood_groups as $group): ?>
            <option value="<?= $group; ?>"><?= $group; ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Submit Button -->
      <div class="flex justify-center">
        <input type="submit" value="Submit Request" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-xl shadow-md transition duration-200 ease-in-out cursor-pointer">
      </div>
    </form>

    <!-- Back Button -->
    <div class="mt-6 text-center">
      <a href="dashboard.php" class="text-red-700 hover:text-red-900 font-medium underline">← Back to Dashboard</a>
    </div>
  </div>

</body>
</html>

<?php $conn->close(); ?>
