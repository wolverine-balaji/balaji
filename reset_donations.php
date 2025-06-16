<?php
header('Content-Type: application/json');
session_start();
$username = $_POST['username'] ?? '';

$response = ['success' => false];

if ($username !== '') {
    $conn = new mysqli("localhost", "root", "", "login");

    if ($conn->connect_error) {
        $response['message'] = "Connection failed.";
        echo json_encode($response);
        exit;
    }

    // Delete all donation records for the user
    $stmt = $conn->prepare("DELETE FROM donors WHERE name = ?");
    $stmt->bind_param("s", $username);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['donationCount'] = 0;
        $response['rewardUnlocked'] = false;
    } else {
        $response['message'] = "Error resetting donations.";
    }

    $stmt->close();
    $conn->close();
}

echo json_encode($response);
?>
