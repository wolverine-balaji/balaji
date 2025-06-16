<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "login");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username'] ?? 'Guest';
    $state = $_POST['state'];
    $district = $_POST['district'];
    $bloodType = $_POST['bloodType'];

    $stmt = $conn->prepare("INSERT INTO request (username, state, district, blood_type, status, requested_at) VALUES (?, ?, ?, ?, 'Pending', NOW())");
    $stmt->bind_param("ssss", $username, $state, $district, $bloodType);

    if ($stmt->execute()) {
        $_SESSION['request_id'] = $stmt->insert_id;
        header("Location: status.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();

// State and Districts
$states = [
    "Tamil Nadu" => ["Chennai", "Coimbatore", "Madurai", "Tiruchirappalli", "Salem"],
    "Maharashtra" => ["Mumbai", "Pune", "Nagpur", "Nashik", "Thane"],
    "Karnataka" => ["Bangalore", "Mysore", "Mangalore", "Hubli", "Belgaum"],
    "Kerala" => ["Thiruvananthapuram", "Kochi", "Kozhikode", "Thrissur", "Alappuzha"],
    "West Bengal" => ["Kolkata", "Howrah", "Durgapur", "Siliguri", "Asansol"]
];

$bloodTypes = ["A+", "A-", "B+", "B-", "O+", "O-", "AB+", "AB-"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Need Blood</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #ff4d4d, #b30000);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0;
    }
    .form-container {
      background: rgba(255, 255, 255, 0.15);
      padding: 40px;
      border-radius: 20px;
      color: white;
      backdrop-filter: blur(10px);
      width: 400px;
      text-align: center;
      border: 1px solid rgba(255, 255, 255, 0.3);
    }
    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
    }
    select, button {
      width: 100%;
      padding: 10px;
      margin-top: 10px;
      border-radius: 8px;
      border: none;
      background-color: #800000;
      color: white;
    }
    button {
      background-color: #4CAF50;
      margin-top: 20px;
      cursor: pointer;
    }
    button:hover {
      background-color: #45a049;
    }
  </style>
</head>
<body>

<div class="form-container">
  <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Guest'); ?>!</h2>
  <h3>🩸 Need Blood Request</h3>
  <form method="POST" action="">
    <label for="state">Select State:</label>
    <select id="state" name="state" onchange="updateDistricts()" required>
      <option value="">-- Select State --</option>
      <?php foreach ($states as $state => $districts): ?>
        <option value="<?= htmlspecialchars($state) ?>"><?= htmlspecialchars($state) ?></option>
      <?php endforeach; ?>
    </select>

    <label for="district">Select District:</label>
    <select id="district" name="district" required>
      <option value="">-- Select District --</option>
    </select>

    <label for="bloodType">Select Blood Type:</label>
    <select id="bloodType" name="bloodType" required>
      <option value="">-- Select Blood Type --</option>
      <?php foreach ($bloodTypes as $blood): ?>
        <option value="<?= htmlspecialchars($blood) ?>"><?= htmlspecialchars($blood) ?></option>
      <?php endforeach; ?>
    </select>

    <button type="submit">Submit Request</button>
  </form>
</div>

<script>
const stateDistricts = <?php echo json_encode($states); ?>;

function updateDistricts() {
  const stateSelect = document.getElementById('state');
  const districtSelect = document.getElementById('district');
  const selectedState = stateSelect.value;

  districtSelect.innerHTML = '<option value="">-- Select District --</option>';

  if (selectedState && stateDistricts[selectedState]) {
    stateDistricts[selectedState].forEach(district => {
      const option = document.createElement('option');
      option.value = district;
      option.textContent = district;
      districtSelect.appendChild(option);
    });
  }
}
</script>

</body>
</html>
