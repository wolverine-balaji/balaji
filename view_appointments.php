<?php
// view_appointments.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $state = $_POST['state'] ?? '';
    $city = $_POST['city'] ?? '';

    // Validate inputs
    if (!empty($state) && !empty($city)) {
        // Connect to database
        $conn = new mysqli("localhost", "root", "", "login");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Query appointments table
        $stmt = $conn->prepare("SELECT hospital_name, appointment_date, address FROM appointments WHERE state = ? AND city = ?");
        $stmt->bind_param("ss", $state, $city);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        die("State and city are required.");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Available Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow p-4">
            <h2 class="mb-4 text-center text-success">Available Appointments</h2>
            
            <?php if (isset($result) && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="alert alert-info">
                        <strong><?= htmlspecialchars($row['hospital_name']) ?></strong><br>
                        Appointment Date: <?= htmlspecialchars(date("F j, Y", strtotime($row['appointment_date']))) ?><br>
                        Address: <?= htmlspecialchars($row['address']) ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="alert alert-warning text-center">
                    No appointments found for <?= htmlspecialchars($city) ?>, <?= htmlspecialchars($state) ?>.
                </div>
            <?php endif; ?>
            
            <div class="text-center mt-4">
                <a href="book_appoint.php" class="btn btn-secondary">Back to Booking</a>
            </div>
        </div>
    </div>
</body>
</html>
