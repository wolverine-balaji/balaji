<?php
session_start();

// Make sure the hospital is logged in
if (!isset($_SESSION['hospital_logged_in']) || !isset($_SESSION['hospital_name'])) {
    header("Location: login.php");
    exit();
}

$loggedInHospital = $_SESSION['hospital_name'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hospital = $_POST['hospital'] ?? '';
    $appointment_date = $_POST['appointment_date'] ?? '';
    $state = $_POST['state'] ?? '';   // Ensure state is passed
    $city = $_POST['city'] ?? '';     // Ensure city is passed
    $address = $_POST['address'] ?? ''; // Ensure address is passed

    // Check if all necessary values are provided
    if (!empty($hospital) && !empty($appointment_date) && !empty($state) && !empty($city) && !empty($address)) {
        
        // Database connection
        $host = "localhost";
        $user = "root";
        $pass = "";
        $dbname = "login";
        $conn = new mysqli($host, $user, $pass, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Insert appointment into the database
        $stmt = $conn->prepare("INSERT INTO appointments (hospital_name, appointment_date, state, city, address) VALUES (?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die("SQL error: " . $conn->error);
        }

        // Bind parameters
        $stmt->bind_param("sssss", $hospital, $appointment_date, $state, $city, $address);

        // Execute the query
       if ($stmt->execute()) {
$_SESSION['appointment_success_message'] = 'Appointment booked successfully on ' . date('F j, Y', strtotime($appointment_date)) . '!';


    echo "
        <!DOCTYPE html>
        <html>
        <head>
            <title>Appointment Success</title>
            <meta http-equiv='refresh' content='4;url=hosp.php'>
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
            <style>
                body {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    height: 100vh;
                    background-color: #f8f9fa;
                }
                .message-box {
                    padding: 30px;
                    border-radius: 10px;
                    background: #d4edda;
                    color: #155724;
                    border: 1px solid #c3e6cb;
                    font-size: 1.2rem;
                    text-align: center;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                }
            </style>
        </head>
        <body>
            <div class='message-box'>
                Appointment booked successfully!<br>
                Redirecting to dashboard in few seconds...
            </div>
        </body>
        </html>
    ";
    exit;
}
 else {
            echo "<script>alert('Failed to book appointment. Please try again later.'); window.history.back();</script>";
            exit;
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    } else {
        echo "<script>alert('Missing information!'); window.history.back();</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hospital Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="mb-4 text-center text-primary">Book Hospital Appointment</h2>

        <form action="appoint.php" method="POST">
            <!-- Display Hospital Name -->
            <div class="mb-3">
                <label class="form-label">Hospital Name</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($loggedInHospital) ?>" readonly>
                <input type="hidden" name="hospital" value="<?= htmlspecialchars($loggedInHospital) ?>">
            </div>

            <!-- State Input -->
            <div class="mb-3">
                <label class="form-label">State</label>
                <select class="form-control" name="state" required>
                    <option value="">Select State</option>
                    <option value="Karnataka">Karnataka</option>
                    <option value="Maharashtra">Maharashtra</option>
                    <option value="Delhi">Delhi</option>
                    <!-- Add more states as needed -->
                </select>
            </div>

            <!-- City Input (Dynamic based on selected state) -->
            <div class="mb-3">
                <label class="form-label">City</label>
                <select class="form-control" name="city" required>
                    <option value="">Select City</option>
                    <option value="Bangalore">Bangalore</option>
                    <option value="Mumbai">Mumbai</option>
                    <option value="Delhi">Delhi</option>
                    <!-- Add cities dynamically based on the selected state -->
                </select>
            </div>

            <!-- Address Input -->
            <div class="mb-3">
                <label class="form-label">Hospital Address</label>
                <input type="text" class="form-control" name="address" required>
            </div>

            <!-- Date Picker -->
            <div class="mb-3">
                <label for="appointment_date" class="form-label">Select Date</label>
                <input type="date" class="form-control" id="appointment_date" name="appointment_date" required min="<?= date('Y-m-d'); ?>">
            </div>

            <!-- Submit Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Submit Appointment</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
