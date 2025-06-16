<?php
session_start();
$username = $_SESSION['username'];

$conn = new mysqli("localhost", "root", "", "login");
if ($conn->connect_error) die("Connection failed.");

$sql = "SELECT status FROM blood_requests WHERE username = ? ORDER BY created_at DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($status);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Waiting Status</title>
</head>
<body>
