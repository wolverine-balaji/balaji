<?php
$conn = new mysqli("localhost", "root", "", "login");

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}
echo "✅ Connected successfully!";
?>
