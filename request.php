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

// Validate input
$state = $_POST['state'] ?? '';
$district = $_POST['district'] ?? '';
$blood_type = $_POST['bloodType'] ?? '';

if ($state && $district && $blood_type) {
    $stmt = $conn->prepare("INSERT INTO request (state, district, blood_type, status, requested_at) VALUES (?, ?, ?, 'pending', NOW())");
    $stmt->bind_param("sss", $state, $district, $blood_type);

    if ($stmt->execute()) {
        $last_id = $stmt->insert_id;
        // Redirect to status page with the request ID
        header("Location: status.php?id=" . $last_id);
        exit;
    } else {
        echo "<script>alert('Failed to submit request. Please try again.'); window.history.back();</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Please fill in all fields.'); window.history.back();</script>";
}

$conn->close();
?>
