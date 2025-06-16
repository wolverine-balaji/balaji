<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "login";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hospital_name = trim($_POST['hospital_name']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']); // No hashing, as requested
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $state = trim($_POST['state']);
    $city = trim($_POST['city']);

    if ($hospital_name && $username && $password && $email && $phone && $address && $state && $city) {
        $check = $conn->prepare("SELECT username, email, phone FROM hospital WHERE username = ? OR email = ? OR phone = ?");
        $check->bind_param("sss", $username, $email, $phone);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $check->bind_result($existing_username, $existing_email, $existing_phone);
            $check->fetch();

            if ($existing_username === $username) {
                $error = "❌ Username already taken.";
            } elseif ($existing_email === $email) {
                $error = "❌ Email already registered.";
            } elseif ($existing_phone === $phone) {
                $error = "❌ Phone number already used.";
            }
        } else {
            $stmt = $conn->prepare("INSERT INTO hospital (hospital_name, username, password, email, phone, address, city, state) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssss", $hospital_name, $username, $password, $email, $phone, $address, $city, $state);

            if ($stmt->execute()) {
                $success = "✅ Registration successful! Redirecting...";
                echo "<script>
                    setTimeout(() => {
                        window.location.href = 'page.php';
                    }, 2000);
                </script>";
            } else {
                $error = "❌ Error: " . $stmt->error;
            }
            $stmt->close();
        }

        $check->close();
    } else {
        $error = "❌ Please fill out all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Hospital Registration</title>
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
<body class="flex items-center justify-center p-6">
  <div class="w-full max-w-2xl bg-white p-8 rounded-2xl shadow-2xl">
    <h2 class="text-3xl font-bold text-red-700 mb-6 text-center">🏥 Hospital Registration</h2>

    <?php if ($success): ?>
      <p class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4 text-center"><?= $success ?></p>
    <?php elseif ($error): ?>
      <p class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4 text-center"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium mb-1">Hospital Name</label>
        <input type="text" name="hospital_name" required class="w-full border border-gray-300 px-4 py-2 rounded-lg">
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Username</label>
        <input type="text" name="username" required class="w-full border border-gray-300 px-4 py-2 rounded-lg">
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Password</label>
        <input type="password" name="password" required class="w-full border border-gray-300 px-4 py-2 rounded-lg">
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Email</label>
        <input type="email" name="email" required class="w-full border border-gray-300 px-4 py-2 rounded-lg">
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Phone</label>
        <input type="tel" name="phone" required class="w-full border border-gray-300 px-4 py-2 rounded-lg">
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Address</label>
        <input type="text" name="address" required class="w-full border border-gray-300 px-4 py-2 rounded-lg">
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">State</label>
        <select name="state" id="state" required class="w-full border border-gray-300 px-4 py-2 rounded-lg" onchange="updateCities()">
          <option value="">Select State</option>
          <option value="Karnataka">Karnataka</option>
          <option value="Maharashtra">Maharashtra</option>
          <option value="Tamil Nadu">Tamil Nadu</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">City</label>
        <select name="city" id="city" required class="w-full border border-gray-300 px-4 py-2 rounded-lg">
          <option value="">Select City</option>
        </select>
      </div>

      <div class="md:col-span-2 text-center mt-4">
        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-semibold shadow-md">
          🚀 Submit
        </button>
      </div>
    </form>
  </div>

  <script>
    const cities = {
      "Karnataka": ["Bengaluru", "Mysuru", "Mangaluru"],
      "Maharashtra": ["Mumbai", "Pune", "Nagpur"],
      "Tamil Nadu": ["Chennai", "Coimbatore", "Madurai"]
    };

    function updateCities() {
      const state = document.getElementById("state").value;
      const citySelect = document.getElementById("city");

      citySelect.innerHTML = '<option value="">Select City</option>';
      if (cities[state]) {
        cities[state].forEach(city => {
          const option = document.createElement("option");
          option.value = city;
          option.textContent = city;
          citySelect.appendChild(option);
        });
      }
    }
  </script>
</body>
</html>

<?php $conn->close(); ?>
