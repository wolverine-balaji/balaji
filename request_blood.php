<?php
session_start();

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "login";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle POST data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
    $hospital = $_POST['hospital'];
    $blood_group = $_POST['blood_group'];

    // Get hospital address from appointments table
    $stmt = $conn->prepare("SELECT address FROM appointments WHERE hospital_name = ? LIMIT 1");
    $stmt->bind_param("s", $hospital);
    $stmt->execute();
    $stmt->bind_result($address);
    $stmt->fetch();
    $stmt->close();

    if ($address) {
        // Insert into blood_requests table
        $insert = $conn->prepare("INSERT INTO blood_requests (username, hospital, address, blood_group, status) VALUES (?, ?, ?, ?, 'pending')");
        $insert->bind_param("ssss", $username, $hospital, $address, $blood_group);

        if ($insert->execute()) {
            $_SESSION['message'] = "Blood request submitted successfully.";
        } else {
            $_SESSION['message'] = "Failed to submit blood request: " . $insert->error;
        }

        $insert->close();
    } else {
        $_SESSION['message'] = "Hospital address not found.";
    }

    $conn->close();
    header("Location: status.php");
    exit();
}
?>
