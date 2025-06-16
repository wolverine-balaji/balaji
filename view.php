<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "login";

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch donor records
$sql = "SELECT MIN(id) as id, name, gender, blood_type, contact_number, email, address, state, city, MIN(created_at) as created_at 
        FROM donor 
        GROUP BY email 
        ORDER BY created_at DESC";
$result = $conn->query($sql);

// Check for SQL query error
if (!$result) {
    die("Error executing query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donor List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #ff9a9e, #fad0c4);
            color: white;
            text-align: center;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            color: black;
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 10px;
            border: 1px solid black;
            text-align: center;
        }
        th {
            background: #ff6f61;
            color: white;
        }
        tr:nth-child(even) {
            background: #f2f2f2;
        }
        button {
            padding: 10px;
            border: none;
            background: #ff6f61;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #e65b50;
        }
    </style>
</head>
<body>
    <h2>Registered Blood Donors</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Gender</th>
            <th>Blood Type</th>
            <th>Contact Number</th>
            <th>Email</th>
            <th>Address</th>
            <th>State</th>
            <th>City</th>
            <th>Registered On</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['gender']; ?></td>
                <td><?php echo $row['blood_type']; ?></td>
                <td><?php echo $row['contact_number']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['address']; ?></td>
                <td><?php echo $row['state']; ?></td>
                <td><?php echo $row['city']; ?></td>
                <td><?php echo $row['created_at']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <button onclick="window.location.href='admin.php';">Back to Dashboard</button>
</body>
</html>

<?php $conn->close(); ?>
