<?php
session_start(); // Start the session to store the request ID

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "login";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request_id = $_POST['request_id'];
    $action = $_POST['action']; // "accepted" or "rejected"

    if (in_array($action, ['accepted', 'rejected'])) {
        // Update the request status in the database
        $stmt = $conn->prepare("UPDATE request SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $action, $request_id);
        if ($stmt->execute()) {
            // Store the request ID in session and redirect to status page
            $_SESSION['request_id'] = $request_id;
            header("Location: status.php"); // No need for id in URL anymore
            exit();
        } else {
            echo "Error updating status: " . $conn->error;
        }
        $stmt->close();
    } else {
        echo "Invalid action!";
    }
}

$conn->close();
?>
