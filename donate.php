<?php
session_start();

$error = "";
$success = "";

// Database connection
$host = "localhost";
$user = "root";
$password = "";
$dbname = "login";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $dob = $_POST["dob"];
    $gender = $_POST["gender"];
    $blood_group = $_POST["blood_group"];
    $contact = trim($_POST["contact"]);
    $email = trim($_POST["email"]);
    $address = trim($_POST["address"]);
    $state = $_POST["state"];
    $city = $_POST["city"];

    // Calculate age from DOB
    $dobDate = new DateTime($dob);
    $currentDate = new DateTime();
    $ageInterval = $dobDate->diff($currentDate);
    $age = $ageInterval->y;

    // Donation date
    $donation_date = date('Y-m-d');

    // Validation
    if (!preg_match("/^[a-zA-Z ]+$/", $name)) {
        $error = "Name can only contain letters and spaces.";
    } elseif (!preg_match("/^[0-9]{10}$/", $contact)) {
        $error = "Contact number must be exactly 10 digits.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (empty($address)) {
        $error = "Address cannot be empty.";
    } elseif (empty($state) || empty($city)) {
        $error = "Please select both state and city.";
    }

    if (empty($error)) {
        // Check if donor exists in donor_backup
        $stmt = $conn->prepare("SELECT donation_count FROM donor_backup WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        $donor = $result->fetch_assoc();
        $stmt->close();

        if ($donor) {
            // Donor exists, increment count
            $donation_count = $donor['donation_count'] + 1;

            $stmt = $conn->prepare("UPDATE donor_backup SET donation_count = ?, donation_date = ? WHERE name = ?");
            $stmt->bind_param("iss", $donation_count, $donation_date, $name);
            $stmt->execute();
            $stmt->close();
        } else {
            // New donor first donation
            $donation_count = 1;
            $stmt = $conn->prepare("INSERT INTO donor_backup (name, age, gender, blood_type, contact_number, email, address, state, city, donation_date, donation_count) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sissssssssi", $name, $age, $gender, $blood_group, $contact, $email, $address, $state, $city, $donation_date, $donation_count);
            $stmt->execute();
            $stmt->close();
        }

        // Insert into donor table (for each donation)
        $stmt = $conn->prepare("INSERT INTO donor (name, age, gender, blood_type, contact_number, email, address, state, city, donation_date, donation_count) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissssssssi", $name, $age, $gender, $blood_group, $contact, $email, $address, $state, $city, $donation_date, $donation_count);

        if ($stmt->execute()) {
            $success = "Form submitted successfully!";
        } else {
            $error = "Database error: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blood Donation Form</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #8e0e00, #1f1c18);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .form-container {
            background: white;
            padding: 30px 40px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 100%;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #e53935;
        }

        label {
            font-weight: 600;
            display: block;
            margin-bottom: 5px;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 15px;
            border-radius: 10px;
            border: 1px solid #ccc;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #e53935;
            outline: none;
        }

        textarea {
            resize: none;
            height: 80px;
        }

        button {
            background-color: #e53935;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 12px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        button:hover {
            background-color: #d32f2f;
        }

        .message {
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .error {
            color: #c62828;
        }

        .success {
            color: #2e7d32;
        }

        #compatibility {
            font-weight: bold;
            margin-top: -10px;
            margin-bottom: 15px;
            color: #b10d2f;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Blood Donation Form</h2>

    <?php if ($error): ?>
        <p class="message error"><?php echo $error; ?></p>
    <?php elseif ($success): ?>
        <p class="message success"><?php echo $success; ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="name">Full Name:</label>
        <input type="text" name="name" required>

        <label for="dob">Date of Birth:</label>
        <input type="date" name="dob" required>

        <label for="gender">Gender:</label>
        <select name="gender" required>
            <option value="">Select</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>

        <label for="blood_group">Blood Group:</label>
        <select name="blood_group" id="blood_group" required onchange="showCompatibility()">
            <option value="">Select</option>
            <option value="A+">A+</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="O+">O+</option>
            <option value="O-">O-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
        </select>
        <div id="compatibility"></div>

        <label for="contact">Contact Number:</label>
        <input type="text" name="contact" required>

        <label for="email">Email Address:</label>
        <input type="email" name="email" required>

        <label for="address">Address:</label>
        <textarea name="address" required></textarea>

        <label for="state">State:</label>
        <select name="state" id="state" required onchange="updateCities()">
            <option value="">Select</option>
            <option value="Tamil Nadu">Tamil Nadu</option>
            <option value="Karnataka">Karnataka</option>
            <option value="Kerala">Kerala</option>
        </select>

        <label for="city">City:</label>
        <select name="city" id="city" required>
            <option value="">Select a state first</option>
        </select>

        <button type="submit">Submit</button>
    </form>
</div>

<script>
    const cityOptions = {
        "Tamil Nadu": ["Chennai", "Salem", "Coimbatore", "Madurai", "Tiruchirapalli"],
        "Karnataka": ["Bangalore", "Mysore", "Mangalore", "Hubli", "Belgaum"],
        "Kerala": ["Kochi", "Thiruvananthapuram", "Kozhikode", "Thrissur", "Alappuzha"]
    };

    function updateCities() {
        const stateSelect = document.getElementById("state");
        const citySelect = document.getElementById("city");
        const selectedState = stateSelect.value;

        citySelect.innerHTML = "<option value=''>Select</option>";
        if (cityOptions[selectedState]) {
            cityOptions[selectedState].forEach(city => {
                const option = document.createElement("option");
                option.value = city;
                option.textContent = city;
                citySelect.appendChild(option);
            });
        }
    }

    function showCompatibility() {
        const group = document.getElementById("blood_group").value;
        const infoBox = document.getElementById("compatibility");

        const compatible = {
            "A+": "A+, AB+",
            "A-": "A+, A-, AB+, AB-",
            "B+": "B+, AB+",
            "B-": "B+, B-, AB+, AB-",
            "O+": "O+, A+, B+, AB+",
            "O-": "All blood groups (universal donor)",
            "AB+": "AB+ only (universal recipient)",
            "AB-": "AB+, AB-"
        };

        infoBox.textContent = group && compatible[group] 
            ? `🩸 Can donate to: ${compatible[group]}` 
            : '';
    }
</script>
</body>
</html>
