<?php
$conn = new mysqli("localhost", "root", "", "login");
if ($conn->connect_error) die("Connection failed");

$id = $_POST['id'];
$action = $_POST['action'];
$status = ($action === 'accept') ? 'accepted' : 'rejected';

$stmt = $conn->prepare("UPDATE blood_requests SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $id);
$stmt->execute();
$stmt->close();
$conn->close();

header("Location: admin.php");
exit();
?>
