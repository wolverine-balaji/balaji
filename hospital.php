<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "login";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$hospitals = $conn->query("SELECT * FROM hospital ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Registered Hospitals</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8 font-sans">
  <div class="max-w-5xl mx-auto bg-white rounded-xl shadow-xl p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-red-700">🏥 Registered Hospitals</h1>
      <a href="hospital_register.php" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow-md">
        ➕ Register New Hospital
      </a>
    </div>

    <?php if ($hospitals->num_rows > 0): ?>
      <div class="overflow-x-auto">
        <table class="min-w-full table-auto border border-gray-300">
          <thead class="bg-red-100">
            <tr>
              <th class="px-4 py-2 border">Hospital Name</th>
              <th class="px-4 py-2 border">Username</th>
              <th class="px-4 py-2 border">Email</th>
              <th class="px-4 py-2 border">Phone</th>
              <th class="px-4 py-2 border">City</th>
              <th class="px-4 py-2 border">State</th>
              <th class="px-4 py-2 border">Registered At</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $hospitals->fetch_assoc()): ?>

              <tr class="hover:bg-gray-100">
                <td class="px-4 py-2 border"><?= htmlspecialchars($row['hospital_name']) ?></td>
                <td class="px-4 py-2 border"><?= htmlspecialchars($row['username']) ?></td>
                <td class="px-4 py-2 border"><?= htmlspecialchars($row['email']) ?></td>
                <td class="px-4 py-2 border"><?= htmlspecialchars($row['phone']) ?></td>
                <td class="px-4 py-2 border"><?= htmlspecialchars($row['city']) ?></td>
                <td class="px-4 py-2 border"><?= htmlspecialchars($row['state']) ?></td>
                <td class="px-4 py-2 border"><?= date("d M Y", strtotime($row['created_at'])) ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p class="text-center text-gray-600">No hospitals registered yet.</p>
    <?php endif; ?>
  </div>
</body>
</html>

<?php $conn->close(); ?>
