<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Fetch feedback from the database
$feedbacks = [];
$conn = new mysqli("localhost", "root", "", "login");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$result = $conn->query("SELECT username, feedback FROM feedback ORDER BY created_at DESC LIMIT 6");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $feedbacks[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Life Saver Blood Bank</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Removed internal CSS -->
    <link rel="stylesheet" href="style.css"> <!-- Optional: add external file -->
</head>
<body>
<header>
    <div>
        <div>
            <div>
                <img src="logo.png" alt="Blood Drop Logo" />
                <span>Life Saver Blood Bank</span>
            </div>
            <a href="about.php">About Us</a>
            <a href="contact.php">Contact</a>
        </div>
        <div class="auth-buttons">
            <?php if (isset($_SESSION['user'])): ?>
                <a href="logout.php" class="btn">Logout</a>
            <?php else: ?>
                <a href="page.php" class="btn" id="loginBtn">
                    <span class="btn-text">Login</span>
                    <i class="fas fa-spinner fa-spin" id="spinner" style="display: none;"></i>
                </a>
                <a href="receipient.php" class="btn">Register</a>
            <?php endif; ?>
        </div>
    </div>
</header>
<section id="hero">
    <video autoplay muted loop id="hero-video">
        <source src="bloodv (1).mp4" type="video/mp4">
    </video>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h2 id="dynamic-heading">Be Someone's Lifeline</h2>
        <div class="cta-buttons">
            <p class="donate-now-text">Donate Now</p>
        </div>
    </div>
</section>
<section>
    <h2>❤️ Why Donate Blood?</h2>
    <p>
        Donating blood is a simple act that saves lives. Your one unit of blood can help up to three patients.
        It’s safe, quick, and greatly needed. Be a hero. Be a donor.
    </p>
</section>
<section>
    <h2>🩸 Features of Blood Donation</h2>
    <div class="features-container">
        <div class="feature-box">
            <i class="fas fa-heartbeat"></i>
            <h3>Saves Lives</h3>
            <p>A single blood donation can help up to three people in need of urgent care or surgery.</p>
        </div>
        <div class="feature-box">
            <i class="fas fa-hand-holding-medical"></i>
            <h3>Boosts Health</h3>
            <p>Regular donation can improve heart health and stimulate new blood cell production.</p>
        </div>
        <div class="feature-box">
            <i class="fas fa-users"></i>
            <h3>Community Impact</h3>
            <p>It strengthens your bond with the community and inspires others to do the same.</p>
        </div>
    </div>
</section>
<section>
    <h2>💬 What People Say</h2>
    <div class="feedback-container">
        <?php if (!empty($feedbacks)): ?>
            <?php foreach ($feedbacks as $fb): ?>
                <div class="feedback-box">
                    <p>“<?= htmlspecialchars($fb['feedback']) ?>”</p>
                    <strong>- <?= htmlspecialchars($fb['username']) ?></strong>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No feedback available yet. Be the first to share your thoughts!</p>
        <?php endif; ?>
    </div>
</section>
<footer>
    <div class="social-icons">
        <a href="#"><i class="fab fa-facebook-f"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-linkedin-in"></i></a>
    </div>
    <p>&copy; 2025 LifeSaver Blood Bank. All Rights Reserved.</p>
</footer>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const headingText = "Be Someone's Lifeline";
    let i = 0;
    const speed = 100;
    const heading = document.getElementById("dynamic-heading");
    heading.innerHTML = "";
    function typeHeading() {
        if (i < headingText.length) {
            heading.innerHTML += headingText.charAt(i);
            i++;
            setTimeout(typeHeading, speed);
        }
    }
    typeHeading();
    const loginBtn = document.getElementById("loginBtn");
    if (loginBtn) {
        let clicked = false;
        loginBtn.addEventListener("click", function (e) {
            if (clicked) return;
            clicked = true;
            e.preventDefault();
            document.getElementById("spinner").style.display = "inline-block";
            setTimeout(() => {
                window.location.href = loginBtn.href;
            }, 800);
        });
    }
});
</script>
</body>
</html>

Page.php
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
// Handle POST login request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    // ✅ Admin Login
    if ($username === "blood" && $password === "bank") {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['role'] = 'admin';
        header("Location: admin.php");
        exit();
    }
    // ✅ Hospital Login
    if (substr($username, 0, 4) === "hosp") {
        $stmt = $conn->prepare("SELECT * FROM hospital WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $hospital = $result->fetch_assoc();
            if ($password === $hospital['password']) {
                $_SESSION['hospital_logged_in'] = true;
                $_SESSION['role'] = 'hospital';
                $_SESSION['username'] = $hospital['username'];
                $_SESSION['hospital_name'] = $hospital['hospital_name'];
                header("Location: hosp.php");
                exit();
            } else {
                echo "<script>alert('Invalid password for hospital account'); window.location.href='login.php';</script>";
                exit();
            }
        } else {
            echo "<script>alert('Hospital account not found'); window.location.href='login.php';</script>";
            exit();
        }
    }
    // ✅ Normal User Login
    $stmt = $conn->prepare("SELECT * FROM receipient WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['photo'] = $user['photo'];
            $_SESSION['role'] = 'user';
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid password'); window.location.href='login.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Username not found'); window.location.href='login.php';</script>";
        exit();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Bank Login</title>
</head>
<body>
    <div>
        <!-- Image placed left -->
        <div>
            <img src="DONATE BLOOD.jpg" alt="World Blood Donor Day" width="300">
        </div>
        <!-- Login box -->
        <div>
            <h2>Life Saver Blood Bank</h2>
            <p>Saving Lives, One Drop at a Time</p>
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required><br><br>
                <input type="password" name="password" placeholder="Password" required><br><br>
                <button type="submit">Login</button>
                <p>New User? <a href="receipient.php">Sign Up</a></p>
            </form>
        </div>
    </div>
</body>
</html>


Recipient.php
<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "login";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$message = "";
$success = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $age = (int) $_POST['age'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $check_sql = "SELECT * FROM receipient WHERE username = ? OR name = ? OR phone = ? OR email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ssss", $username, $name, $phone, $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    if ($result->num_rows > 0) {
        $message = "❌ User with same username, name, phone or email already exists.";
    } else {
        $sql = "INSERT INTO receipient (name, age, gender, phone, email, address, username, password) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sissssss", $name, $age, $gender, $phone, $email, $address, $username, $password);
            if ($stmt->execute()) {
                $_SESSION['username'] = $username;
                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email;
                $success = true;
            } else {
                $message = "❌ Database error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "❌ Database preparation error: " . $conn->error;
        }
    }
    $check_stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Registration</title>
</head>
<body>
<div>
  <h2>User Registration</h2>
  <?php if (!empty($message)) : ?>
    <div><?= $message; ?></div>
  <?php endif; ?>
  <?php if ($success) : ?>
    <script>
      alert('✅ Form registered successfully!');
      setTimeout(() => {
        window.location.href = 'page.php';
      }, 5000);
    </script>
  <?php endif; ?>
  <form method="POST">
    <div>
      <label for="name">Name</label>
      <input type="text" id="name" name="name" required>
    </div>
    <div>
      <label for="age">Age</label>
      <input type="number" id="age" name="age" required>
    </div>
    <div>
      <label>Gender</label>
      <div>
        <label><input type="radio" name="gender" value="Male" required> Male</label>
        <label><input type="radio" name="gender" value="Female" required> Female</label>
        <label><input type="radio" name="gender" value="Other" required> Other</label>
      </div>
    </div>
    <div>
      <label for="phone">Phone Number</label>
      <input type="text" id="phone" name="phone" maxlength="10" required>
    </div>
    <div>
      <label for="email">Email</label>
      <input type="email" id="email" name="email" required>
    </div>
    <div>
      <label for="address">Address</label>
      <textarea id="address" name="address" rows="3" required></textarea>
    </div>
    <div>
      <label for="username">Username</label>
      <input type="text" id="username" name="username" required>
    </div>
    <div>
      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>
    </div>
    <button type="submit">Submit</button>
  </form>
</div>
<footer>
  <p>LEGAL RESTRICTIONS AND TERMS OF USE APPLICABLE TO THIS SITE</p>
  <p>Use of this site signifies your agreement to the terms of use.</p>
  <p>© 2025 Life Saver Blood Bank. All rights reserved.</p>
</footer>
</body>
</html>

Dashboard.php
<?php
session_start();
$username = $_SESSION['username'] ?? 'Guest';
$userId = $_SESSION['user_id'] ?? 0;
$donation_count = 0;
$upcomingDrives = [];
$message = '';  // Variable to hold success or error messages
if ($userId > 0) {
    // Count donations by user ID
    // No need to query the database, just assume donations are counted as per previous logic
    $donation_count = 0; // Assuming a default count here
}
// Fetch upcoming donation camps from the JSON file
$upcomingDrivesFile = 'donation_drives.json';
if (file_exists($upcomingDrivesFile)) {
    $upcomingDrives = json_decode(file_get_contents($upcomingDrivesFile), true);
}
// Sort the drives by date (newest first)
usort($upcomingDrives, function ($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});
// Get the latest drive (the first element in the sorted array)
$latestDrive = !empty($upcomingDrives) ? $upcomingDrives[0] : null;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $driveDate = $_POST['date'];
    $driveTime = $_POST['time'];
    $driveLocality = $_POST['locality'];
    $driveDistrict = $_POST['district'];
    $driveState = $_POST['state'];
    // Validate input
    if (!empty($driveDate) && !empty($driveTime) && !empty($driveLocality) && !empty($driveDistrict) && !empty($driveState)) {
        // Create new donation drive entry
        $newDrive = [
            'date' => $driveDate,
            'time' => $driveTime,
            'locality' => $driveLocality,
            'district' => $driveDistrict,
            'state' => $driveState
        ];
        // Append the new drive to the existing list of upcoming drives
        $upcomingDrives[] = $newDrive;
        // Save the updated list to the JSON file
        if (file_put_contents($upcomingDrivesFile, json_encode($upcomingDrives, JSON_PRETTY_PRINT))) {
            $message = "Donation drive successfully scheduled!";
        } else {
            $message = "Error scheduling the donation drive. Please try again.";
        }
    } else {
        $message = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Life Saver Blood Bank</title>
</head>
<body>
<div>
    <h3><?php echo htmlspecialchars($username); ?></h3>
    <a href="user.php">👤 Profile</a>
    <a href="reward.php">🏆 Reward</a>
    <a href="page.php">🚪 Logout</a>
</div>
<div>
    <h1>Life Saver Blood Bank</h1>
    <div>
        Welcome, <strong><?php echo htmlspecialchars($username); ?></strong>! 🌟<br>
        Thank you for being a real-life hero
    </div>
    <div>
        <a href="need.php">🩸<br>Need Blood</a>
        <a href="donateoptions.php">❤️<br>Donate Blood</a>
        <a href="donor.php">👩‍⚕️<br>Find Donors</a>
        <a href="feedback.php">💬<br>Feedback</a>
    </div>
    <div>
        <h2>Upcoming Donation Drives</h2>
        <?php if ($latestDrive) { ?>
            <div>
                <strong>Drive Date:</strong> <?php echo htmlspecialchars($latestDrive['date']); ?><br>
                <strong>Time:</strong> <?php echo htmlspecialchars($latestDrive['time']); ?><br>
                <strong>Location:</strong> <?php echo htmlspecialchars($latestDrive['locality']) . ', ' . htmlspecialchars($latestDrive['city']) . ', ' . htmlspecialchars($latestDrive['state']); ?>
            </div>
        <?php } else { ?>
            <p>No upcoming drives at the moment.</p>
        <?php } ?>
    </div>
    <!-- Success/Error message -->
    <?php if ($message) { ?>
        <div>
            <?php echo $message; ?>
        </div>
    <?php } ?>
</div>
</body>
</html>

User.php
<?php
session_start();
// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: page.php");
    exit();
}
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "login";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
// Fetch user details
$sql = "SELECT * FROM receipient WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
</head>
<body>
<div>
    <div>
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
    </div>
    <div>
        <div>
            <div>
                <div>User Name:</div>
                <div><?php echo htmlspecialchars($user['username']); ?></div>
            </div>
            <div>
                <div>Full Name:</div>
                <div><?php echo htmlspecialchars($user['name']); ?></div>
            </div>
            <div>
                <div>Phone:</div>
                <div><?php echo htmlspecialchars($user['phone']); ?></div>
            </div>
            <div>
                <div>Age:</div>
                <div><?php echo htmlspecialchars($user['age']); ?></div>
            </div>
            <div>
                <div>Gender:</div>
                <div><?php echo htmlspecialchars($user['gender']); ?></div>
            </div>
            <div>
                <div>Email:</div>
                <div><?php echo htmlspecialchars($user['email']); ?></div>
            </div>
            <div>
                <div>Address:</div>
                <div><?php echo htmlspecialchars($user['address']); ?></div>
            </div>
        </div>
        <div>
            <form action="dashboard.php" method="POST">
                <button type="submit">Back to Dashboard</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>

Reward.php
<?php
session_start();
$username = $_SESSION['username'] ?? '';
if (empty($username)) {
    echo json_encode(['error' => 'Username is not set in session']);
    exit;
}
$conn = new mysqli("localhost", "root", "", "login");
if ($conn->connect_error) {
    echo json_encode(['error' => 'Failed to connect to the database: ' . $conn->connect_error]);
    exit;
}
// Ensure user row exists in donor_backup table
$stmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM donor_backup WHERE name = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();
if ($row['cnt'] == 0) {
    $stmt = $conn->prepare("INSERT INTO donor_backup (name, donation_count) VALUES (?, 0)");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->close();
}
// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reset']) && $_POST['reset'] === 'true') {
        $response = ['success' => false, 'donationCount' => 0, 'rewardUnlocked' => false];
        $stmt = $conn->prepare("SELECT donation_count FROM donor_backup WHERE name = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $donationCount = $data['donation_count'] ?? 0;
        $stmt->close();
        if ($donationCount > 0) {
            $stmt = $conn->prepare("UPDATE donor_backup SET donation_count = 0 WHERE name = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->close();
            $donationCount = 0;
        }
        $response['success'] = true;
        $response['donationCount'] = $donationCount;
        $response['rewardUnlocked'] = $donationCount >= 4;
        echo json_encode($response);
        $conn->close();
        exit;
    }
    if (isset($_POST['increment']) && $_POST['increment'] === 'true') {
        $response = ['success' => false, 'donationCount' => 0, 'rewardUnlocked' => false];
        $stmt = $conn->prepare("UPDATE donor_backup SET donation_count = donation_count + 1 WHERE name = ?");
        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->close();
            $stmt = $conn->prepare("SELECT donation_count FROM donor_backup WHERE name = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            $donationCount = $data['donation_count'] ?? 0;
            $stmt->close();
            $response['success'] = true;
            $response['donationCount'] = $donationCount;
            $response['rewardUnlocked'] = $donationCount >= 4;
        } else {
            $response['error'] = 'Failed to prepare increment statement: ' . $conn->error;
        }
        echo json_encode($response);
        $conn->close();
        exit;
    }
}
header('Content-Type: text/html; charset=utf-8');
$statesCities = [
    'Andhra Pradesh' => ['Visakhapatnam', 'Vijayawada', 'Guntur'],
    'Tamil Nadu' => ['Chennai', 'Coimbatore', 'Madurai'],
    'Karnataka' => ['Bangalore', 'Mysore', 'Hubli'],
    'Maharashtra' => ['Mumbai', 'Pune', 'Nagpur'],
    'Uttar Pradesh' => ['Lucknow', 'Kanpur', 'Varanasi'],
    'Delhi' => ['Delhi'],
    'West Bengal' => ['Kolkata', 'Howrah', 'Siliguri'],
    'Gujarat' => ['Ahmedabad', 'Surat', 'Vadodara'],
    'Rajasthan' => ['Jaipur', 'Udaipur', 'Jodhpur'],
    'Punjab' => ['Chandigarh', 'Amritsar', 'Ludhiana']
];
$goal = 4;
$donationCount = 0;
$rewardUnlocked = false;
$stmt = $conn->prepare("SELECT donation_count FROM donor_backup WHERE name = ?");
if ($stmt) {
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $donationCount = $data['donation_count'] ?? 0;
    $stmt->close();
}
$conn->close();
$rewardUnlocked = $donationCount >= $goal;
$progressPercent = min(($donationCount / $goal) * 100, 100);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Your Reward Progress</title>
</head>
<body>
    <div>
        <h2>Your Donation Journey</h2>
        <p>You've donated <strong><?php echo $donationCount; ?></strong> time<?php echo $donationCount == 1 ? '' : 's'; ?>!</p>
        <div>
            <div style="width: <?php echo $progressPercent; ?>%; background: green; height: 20px;"></div>
        </div>
        <p><strong>Goal:</strong> 4 Donations</p>
        <div>
            <?php if ($rewardUnlocked): ?>
                Congratulations! You've earned <strong>25% OFF</strong> on a full body checkup at our partnered hospital!
            <?php else: ?>
                Keep going! Donate <?php echo $goal - $donationCount; ?> more time<?php echo ($goal - $donationCount) == 1 ? '' : 's'; ?> to unlock your reward!
            <?php endif; ?>
        </div>
        <?php if ($rewardUnlocked): ?>
            <form id="donationForm" action="hospital_info.php" method="POST">
                <label for="state">Select State:</label><br />
                <select name="state" id="state" required>
                    <option value="">Select a state</option>
                    <?php foreach ($statesCities as $state => $cities): ?>
                        <option value="<?php echo htmlspecialchars($state); ?>"><?php echo htmlspecialchars($state); ?></option>
                    <?php endforeach; ?>
                </select><br><br>
                <label for="city">Select City:</label><br />
                <select name="city" id="city" required>
                    <option value="">Select a city</option>
                </select><br><br>
                <button type="submit">Submit</button>
            </form>
        <?php endif; ?>
        <button id="resetButton">Reset Donations</button>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
<script>
    const statesCities = <?php echo json_encode($statesCities); ?>;
    const stateSelect = document.getElementById('state');
    const citySelect = document.getElementById('city');
    const resetButton = document.getElementById('resetButton');
    if (stateSelect) {
        stateSelect.addEventListener('change', () => {
            const selectedState = stateSelect.value;
            citySelect.innerHTML = '<option value="">Select a city</option>';
            if (statesCities[selectedState]) {
                statesCities[selectedState].forEach(city => {
                    const option = document.createElement('option');
                    option.value = city;
                    option.textContent = city;
                    citySelect.appendChild(option);
                });
            }
        });
    }
    resetButton.addEventListener('click', () => {
        fetch('', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'reset=true'
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to reset donation count.');
            }
        })
        .catch(err => alert('Error: ' + err));
    });
</script>
</body>
</html>

Need.php
<?php
session_start();
// Database connection
$conn = new mysqli("localhost", "root", "", "login");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $state = $_POST['state'];
    $district = $_POST['district'];
    $bloodType = $_POST['bloodType'];
    $stmt = $conn->prepare("INSERT INTO request (state, district, blood_type, status) VALUES (?, ?, ?, 'pending')");
    $stmt->bind_param("sss", $state, $district, $bloodType);
    if ($stmt->execute()) {
        $_SESSION['request_id'] = $stmt->insert_id; // Save the inserted request ID in session
        header("Location: status.php"); // Redirect to the status page
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
// Setup for form (if not submitted yet)
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
// State and Districts
$states = [
    "Tamil Nadu" => ["Chennai", "Coimbatore", "Madurai", "Tiruchirappalli", "Salem"],
    "Maharashtra" => ["Mumbai", "Pune", "Nagpur", "Nashik", "Thane"],
    "Karnataka" => ["Bangalore", "Mysore", "Mangalore", "Hubli", "Belgaum"],
    "Kerala" => ["Thiruvananthapuram", "Kochi", "Kozhikode", "Thrissur", "Alappuzha"],
    "West Bengal" => ["Kolkata", "Howrah", "Durgapur", "Siliguri", "Asansol"]
];
$bloodTypes = ["A+", "A-", "B+", "B-", "O+", "O-", "AB+", "AB-"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Need Blood</title>
</head>
<body>
<div>
  <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
  <h3>🩸 Need Blood Request</h3>
  <form method="POST" action="">
    <label for="state">Select State:</label>
    <select id="state" name="state" onchange="updateDistricts()" required>
      <option value="">-- Select State --</option>
      <?php foreach ($states as $state => $districts): ?>
        <option value="<?= htmlspecialchars($state) ?>"><?= htmlspecialchars($state) ?></option>
      <?php endforeach; ?>
    </select>
    <label for="district">Select District:</label>
    <select id="district" name="district" required>
      <option value="">-- Select District --</option>
    </select>
    <label for="bloodType">Select Blood Type:</label>
    <select id="bloodType" name="bloodType" required>
      <option value="">-- Select Blood Type --</option>
      <?php foreach ($bloodTypes as $blood): ?>
        <option value="<?= htmlspecialchars($blood) ?>"><?= htmlspecialchars($blood) ?></option>
      <?php endforeach; ?>
    </select>
    <button type="submit">Submit Request</button>
  </form>
</div>
<script>
const stateDistricts = <?php echo json_encode($states); ?>;
function updateDistricts() {
  const stateSelect = document.getElementById('state');
  const districtSelect = document.getElementById('district');
  const selectedState = stateSelect.value;
  districtSelect.innerHTML = '<option value="">-- Select District --</option>';
  if (selectedState && stateDistricts[selectedState]) {
    stateDistricts[selectedState].forEach(district => {
      const option = document.createElement('option');
      option.value = district;
      option.textContent = district;
      districtSelect.appendChild(option);
    });
  }
}
</script>
</body>
</html>

Donateoptions.php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate Blood Options</title>
</head>
<body>
    <div>
        <h1>Choose How You'd Like to Donate</h1>
        <div>
            <a href="donate.php">
                <span>🩸</span>
                Join a Blood Camp
            </a>
            <a href="book_appoint.php">
                <span>🏥</span>
                Find Appointment in Hospital
            </a>
        </div>
    </div>
</body>
</html>

Donor.php
<?php
$states = [
    "Tamil Nadu" => ["Chennai", "Coimbatore", "Madurai", "Tiruchirappalli", "Salem"],
    "Maharashtra" => ["Mumbai", "Pune", "Nagpur", "Nashik", "Thane"],
    "Karnataka" => ["Bangalore", "Mysore", "Mangalore", "Hubli", "Belgaum"],
    "Kerala" => ["Thiruvananthapuram", "Kochi", "Kozhikode", "Thrissur", "Alappuzha"],
    "West Bengal" => ["Kolkata", "Howrah", "Durgapur", "Siliguri", "Asansol"]
];
$bloodTypes = ["A+", "A-", "B+", "B-", "O+", "O-", "AB+", "AB-"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Find Donor</title>
</head>
<body>
    <div>
        <h2>Find a Blood Donor</h2>
        <form action="donor_results.php" method="GET">
            <label for="state">Select State:</label>
            <select id="state" name="state" onchange="updateDistricts()" required>
                <option value="">-- Select State --</option>
                <?php foreach ($states as $state => $districts): ?>
                    <option value="<?php echo $state; ?>"><?php echo $state; ?></option>
                <?php endforeach; ?>
            </select>
            <label for="district">Select District:</label>
            <select id="district" name="district" required>
                <option value="">-- Select District --</option>
            </select>
            <label for="blood_type">Select Blood Group:</label>
            <select id="blood_type" name="blood_type" required>
                <option value="">-- Select Blood Group --</option>
                <?php foreach ($bloodTypes as $blood): ?>
                    <option value="<?php echo $blood; ?>"><?php echo $blood; ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Search Donors</button>
        </form>
    </div>
    <script>
        const districtsByState = <?php echo json_encode($states); ?>;
        function updateDistricts() {
            const state = document.getElementById("state").value;
            const districtSelect = document.getElementById("district");
            districtSelect.innerHTML = '<option value="">-- Select District --</option>';
            if (districtsByState[state]) {
                districtsByState[state].forEach(district => {
                    const option = document.createElement("option");
                    option.value = district;
                    option.textContent = district;
                    districtSelect.appendChild(option);
                });
            }
        }
    </script>
</body>
</html>

Feedback.php
<?php
session_start();
// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: page.php");
    exit();
}
$feedbackSaved = false;
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $feedback = trim($_POST['feedback']);
    if (!empty($feedback)) {
        $conn = new mysqli("localhost", "root", "", "login");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $stmt = $conn->prepare("INSERT INTO feedback (user_id, username, feedback, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iss", $user_id, $username, $feedback);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        $feedbackSaved = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Feedback - Lifesaver Blood Bank</title>
</head>
<body>
<div>
    <h2>We Value Your Feedback ❤️</h2>
    <?php if ($feedbackSaved): ?>
        <div>
            Thank you for your feedback, <?php echo htmlspecialchars($_SESSION['username']); ?>!
        </div>
    <?php else: ?>
        <form method="POST">
            <textarea name="feedback" placeholder="Share your experience or suggestions..." required></textarea>
            <button type="submit">Submit Feedback</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>

Status.php
<?php
session_start();
$conn = new mysqli("localhost", "root", "", "login");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$requestId = $_SESSION['request_id'] ?? null;
$message = "⚠️ Something went wrong. Please verify your request.";
$debugInfo = ""; // store debug information separately
if ($requestId) {
    $debugInfo .= "DEBUG: Request ID = " . $requestId . "<br>";
    $stmt = $conn->prepare("SELECT status FROM request WHERE id = ?");
    $stmt->bind_param("i", $requestId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $debugInfo .= "DEBUG: Found row in DB<br>";
        $status = $row['status'];
        $debugInfo .= "DEBUG: Status = " . $status . "<br>";
        switch ($status) {
            case 'pending':
                $message = "⏳ Your blood request is pending.";
                break;
            case 'accepted':
                $message = "✅ Your blood request is accepted.";
                break;
            case 'rejected':
                $message = "❌ Due to low stocks, your request is rejected.";
                break;
            default:
                $message = "⚠️ Unknown status: " . htmlspecialchars($status);
                break;
        }
    } else {
        $debugInfo .= "DEBUG: No row found for this request ID<br>";
    }
    $stmt->close();
} else {
    $debugInfo .= "DEBUG: No request ID in session<br>";
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Status</title>
</head>
<body>
<div>
    <div>
        <?php echo $message; ?>
    </div>
    <!-- Back Button -->
    <a href="need.php">⬅ Go Back to Request Page</a>
    <!-- Debug Info (remove later if not needed) -->
    <div>
        <?php echo $debugInfo; ?>
    </div>
</div>
</body>
</html>

Donate.php
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
</head>
<body>
<div>
    <h2>Blood Donation Form</h2>
    <?php if ($error): ?>
        <p><?php echo $error; ?></p>
    <?php elseif ($success): ?>
        <p><?php echo $success; ?></p>
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
            ? `Can donate to: ${compatible[group]}` 
            : '';
    }
</script>
</body>
</html>

Book_appointment.php
<!-- book_appoint.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Book Appointment</title>
</head>
<body>
    <div>
        <h2>Select Your Location</h2>
        <form method="POST" action="view_appointments.php">
            <div>
                <label for="state">State</label>
                <select id="state" name="state" required>
                    <option value="" disabled selected>Choose a state</option>
                    <option value="Tamil Nadu">Tamil Nadu</option>
                    <option value="Karnataka">Karnataka</option>
                    <!-- Add more states as needed -->
                </select>
            </div>
            <div>
                <label for="city">City</label>
                <select id="city" name="city" required>
                    <option value="" disabled selected>Choose a city</option>
                    <option value="Chennai">Chennai</option>
                    <option value="Bangalore">Bangalore</option>
                    <!-- Add more cities as needed -->
                </select>
            </div>
            <div>
                <button type="submit">View Appointments</button>
            </div>
        </form>
    </div>
</body>
</html>



view_appointments.php
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
</head>
<body>
    <div>
        <h2>Available Appointments</h2>
        <?php if (isset($result) && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div>
                    <strong><?php echo htmlspecialchars($row['hospital_name']); ?></strong><br>
                    Appointment Date: <?php echo htmlspecialchars(date("F j, Y", strtotime($row['appointment_date']))); ?><br>
                    Address: <?php echo htmlspecialchars($row['address']); ?>
                </div>
                <br>
            <?php endwhile; ?>
        <?php else: ?>
            <div>
                No appointments found for <?php echo htmlspecialchars($city); ?>, <?php echo htmlspecialchars($state); ?>.
            </div>
        <?php endif; ?>
        <div>
            <a href="book_appoint.php">Back to Booking</a>
        </div>
    </div>
</body>
</html>

donor_results.php 
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
// Get filter values
$state = $_GET['state'] ?? '';
$district = $_GET['district'] ?? '';
$blood_type = $_GET['blood_type'] ?? '';
// Correct query using your DB column names
$query = "SELECT name, contact, email, state, city, blood_group, created_at FROM donors 
          WHERE state = ? AND city = ? AND blood_group = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Query Prepare Failed: " . $conn->error); // Shows exact SQL error
}
$stmt->bind_param("sss", $state, $district, $blood_type);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donor Results</title>
</head>
<body>
    <h2>Available Donors in <?php echo htmlspecialchars($district); ?>, <?php echo htmlspecialchars($state); ?> (<?php echo htmlspecialchars($blood_type); ?>)</h2>
    <div>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div>
                    <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($row['contact']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                    <p><strong>Blood Type:</strong> <?php echo htmlspecialchars($row['blood_group']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($row['city']) . ', ' . htmlspecialchars($row['state']); ?></p>
                    <p><strong>Joined:</strong> <?php echo date("d M Y", strtotime($row['created_at'])); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No donors found for the selected filters.</p>
        <?php endif; ?>
    </div>
    <a href="donor.php">← Back to Search</a>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>

hospital.php
<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "login";
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$hospitals = $conn->query("SELECT * FROM hospital ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Registered Hospitals</title>
</head>
<body>
  <div>
    <div>
      <h1>🏥 Registered Hospitals</h1>
      <a href="hospital_register.php">
        ➕ Register New Hospital
      </a>
    </div>
    <?php if ($hospitals->num_rows > 0): ?>
      <div>
        <table border="1">
          <thead>
            <tr>
              <th>Hospital Name</th>
              <th>Username</th>
              <th>Email</th>
              <th>Phone</th>
              <th>City</th>
              <th>State</th>
              <th>Registered At</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $hospitals->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['hospital_name']) ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><?= htmlspecialchars($row['city']) ?></td>
                <td><?= htmlspecialchars($row['state']) ?></td>
                <td><?= date("d M Y", strtotime($row['created_at'])) ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p>No hospitals registered yet.</p>
    <?php endif; ?>
  </div>
</body>
</html>
<?php $conn->close(); ?>

hospital_register.php
<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "login";
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$success = $error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hospital_name = trim($_POST['hospital_name']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']); // No hashing, as requested
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $state = trim($_POST['state']);
    $city = trim($_POST['city']);
    if ($hospital_name && $username && $password && $email && $phone && $address && $state && $city) {
        $check = $conn->prepare("SELECT username, email, phone FROM hospital WHERE username = ? OR email = ? OR phone = ?");
        $check->bind_param("sss", $username, $email, $phone);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $check->bind_result($existing_username, $existing_email, $existing_phone);
            $check->fetch();
            if ($existing_username === $username) {
                $error = "❌ Username already taken.";
            } elseif ($existing_email === $email) {
                $error = "❌ Email already registered.";
            } elseif ($existing_phone === $phone) {
                $error = "❌ Phone number already used.";
            }
        } else {
            $stmt = $conn->prepare("INSERT INTO hospital (hospital_name, username, password, email, phone, address, city, state) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssss", $hospital_name, $username, $password, $email, $phone, $address, $city, $state);
            if ($stmt->execute()) {
                $success = "✅ Registration successful! Redirecting...";
                echo "<script>
                    setTimeout(() => {
                        window.location.href = 'page.php';
                    }, 2000);
                </script>";
            } else {
                $error = "❌ Error: " . $stmt->error;
            }
            $stmt->close();
        }
        $check->close();
    } else {
        $error = "❌ Please fill out all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Hospital Registration</title>
</head>
<body>
  <h2>🏥 Hospital Registration</h2>
  <?php if ($success): ?>
    <p><?= $success ?></p>
  <?php elseif ($error): ?>
    <p><?= $error ?></p>
  <?php endif; ?>
  <form method="POST">
    <div>
      <label>Hospital Name</label>
      <input type="text" name="hospital_name" required>
    </div>
    <div>
      <label>Username</label>
      <input type="text" name="username" required>
    </div>
    <div>
      <label>Password</label>
      <input type="password" name="password" required>
    </div>
    <div>
      <label>Email</label>
      <input type="email" name="email" required>
    </div>
    <div>
      <label>Phone</label>
      <input type="tel" name="phone" required>
    </div>
    <div>
      <label>Address</label>
      <input type="text" name="address" required>
    </div>
    <div>
      <label>State</label>
      <select name="state" id="state" required onchange="updateCities()">
        <option value="">Select State</option>
        <option value="Karnataka">Karnataka</option>
        <option value="Maharashtra">Maharashtra</option>
        <option value="Tamil Nadu">Tamil Nadu</option>
      </select>
    </div>
    <div>
      <label>City</label>
      <select name="city" id="city" required>
        <option value="">Select City</option>
      </select>
    </div>
    <div>
      <button type="submit">Submit</button>
    </div>
  </form>
  <script>
    const cities = {
      "Karnataka": ["Bengaluru", "Mysuru", "Mangaluru"],
      "Maharashtra": ["Mumbai", "Pune", "Nagpur"],
      "Tamil Nadu": ["Chennai", "Coimbatore", "Madurai"]
    };
    function updateCities() {
      const state = document.getElementById("state").value;
      const citySelect = document.getElementById("city");
      citySelect.innerHTML = '<option value="">Select City</option>';
      if (cities[state]) {
        cities[state].forEach(city => {
          const option = document.createElement("option");
          option.value = city;
          option.textContent = city;
          citySelect.appendChild(option);
        });
      }
    }
  </script>
</body>
</html>
<?php $conn->close(); ?>

stock.php
<?php
// DB connection
$host = "localhost";
$user = "root";
$password = "";
$dbname = "login";
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Fetch blood stock data
$sql = "SELECT blood_group, COUNT(*) AS units_available FROM donors GROUP BY blood_group";
$result = $conn->query($sql);
$bloodGroups = [];
$units = [];
$lowStock = [];
while ($row = $result->fetch_assoc()) {
    $bloodGroups[] = $row['blood_group'];
    $units[] = $row['units_available'];
    if ((int)$row['units_available'] < 3) {
        $lowStock[] = $row['blood_group'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Blood Stock Dashboard</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <h2>Blood Stock Bar Chart</h2>
  <canvas id="barChart"></canvas>
  <h2>Blood Stock Distribution (Pie)</h2>
  <canvas id="pieChart"></canvas>
  <?php if (!empty($lowStock)): ?>
    <h3>Low Stock Alert</h3>
    <p>The following blood groups have low stock (less than 3 units):</p>
    <p><?php echo implode(", ", $lowStock); ?></p>
  <?php endif; ?>
  <script>
    const bloodGroups = <?php echo json_encode($bloodGroups); ?>;
    const units = <?php echo json_encode($units); ?>;
    const colors = ['#ef4444', '#f97316', '#facc15', '#22c55e', '#3b82f6', '#8b5cf6', '#ec4899', '#14b8a6'];
    // Bar Chart
    new Chart(document.getElementById('barChart').getContext('2d'), {
      type: 'bar',
      data: {
        labels: bloodGroups,
        datasets: [{
          label: 'Units Available',
          data: units,
          backgroundColor: colors,
          borderRadius: 0 // removed rounded styling
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
          title: {
            display: true,
            text: 'Blood Group Availability'
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: { precision: 0 }
          }
        }
      }
    });
    // Pie Chart
    new Chart(document.getElementById('pieChart').getContext('2d'), {
      type: 'pie',
      data: {
        labels: bloodGroups,
        datasets: [{
          label: 'Blood Group Share',
          data: units,
          backgroundColor: colors
        }]
      },
      options: {
        responsive: true,
        plugins: {
          title: {
            display: true,
            text: 'Blood Stock Percentage'
          }
        }
      }
    });
  </script>
</body>
</html>

view.php
<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "login";
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT id, name, dob, gender, blood_group, contact, email, address, state, city, created_at FROM donors ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donor List</title>
</head>
<body>
    <h2>Registered Blood Donors</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>DOB</th>
            <th>Gender</th>
            <th>Blood Group</th>
            <th>Contact</th>
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
                <td><?php echo $row['dob']; ?></td>
                <td><?php echo $row['gender']; ?></td>
                <td><?php echo $row['blood_group']; ?></td>
                <td><?php echo $row['contact']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['address']; ?></td>
                <td><?php echo $row['state']; ?></td>
                <td><?php echo $row['city']; ?></td>
                <td><?php echo $row['created_at']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <br>
    <button onclick="window.location.href='admin.php';">Back to Dashboard</button>
</body>
</html>
<?php $conn->close(); ?>

drive.php
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['drive_date'] ?? '';
    $time = $_POST['drive_time'] ?? '';
    $state = $_POST['state'] ?? '';
    $city = $_POST['city'] ?? '';
    $locality = $_POST['locality'] ?? '';
    $jsonFile = 'donation_drives.json';
    if (file_exists($jsonFile)) {
        $existingDrives = json_decode(file_get_contents($jsonFile), true);
    } else {
        $existingDrives = [];
    }
    $newDrive = [
        'date' => $date,
        'time' => $time,
        'state' => $state,
        'city' => $city,
       'locality' => $locality
    ];
    $existingDrives[] = $newDrive;
    if (file_put_contents($jsonFile, json_encode($existingDrives, JSON_PRETTY_PRINT))) {
        echo "<script>alert('Blood Drive Scheduled Successfully'); window.location.href='drive.php';</script>";
    } else {
        echo "<script>alert('Error: Could not save the data.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Schedule Blood Drive</title>
</head>
<body>
  <form method="POST">
    <h2>Schedule Blood Donation</h2>
    <div>
      <label>Select Date</label><br>
      <input type="date" name="drive_date" required>
    </div>
    <br>
    <div>
      <label>Select Time (24-hour format)</label><br>
      <select name="drive_time" required>
        <option value="">Select Time</option>
        <?php
        for ($hour = 9; $hour <= 16; $hour++) {
            $formatted = sprintf("%02d:00", $hour);
            echo "<option value='$formatted'>$formatted</option>";
        }
        ?>
      </select>
    </div>
    <br>
    <div>
      <label>State</label><br>
      <select name="state" required>
        <option value="">Select State</option>
        <option value="Tamil Nadu">Tamil Nadu</option>
        <option value="Karnataka">Karnataka</option>
        <option value="Kerala">Kerala</option>
      </select>
    </div>
    <br>
    <div>
      <label>City</label><br>
      <select name="city" required>
        <option value="">Select City</option>
        <option value="Chennai">Chennai</option>
        <option value="Bangalore">Bangalore</option>
        <option value="Kochi">Kochi</option>
        <option value="Coimbatore">Coimbatore</option>
        <option value="Mysore">Mysore</option>
        <option value="Thrissur">Thrissur</option>
      </select>
    </div>
    <br>
    <div>
      <label>Locality</label><br>
      <input type="text" name="locality" required placeholder="e.g., Anna Nagar, MG Road...">
    </div>
    <br>
    <button type="submit">Submit</button>
    <br><br>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $date = $_POST['drive_date'];
        $time = $_POST['drive_time'];
        $state = $_POST['state'];
        $city = $_POST['city'];
        $locality = $_POST['locality'];
    ?>
      <div>
        <p>Drive scheduled on <strong><?= htmlspecialchars($date) ?></strong> at <strong><?= htmlspecialchars($time) ?></strong></p>
        <p>Location: <strong><?= htmlspecialchars($locality) ?>, <?= htmlspecialchars($city) ?>, <?= htmlspecialchars($state) ?></strong></p>
      </div>
    <?php } ?>
  </form>
</body>
</html>

hosp.php
<?php
session_start();
// Example session variable setup
$hospitalName = isset($_SESSION['hospital_name']) ? $_SESSION['hospital_name'] : 'Hospital Partner';
// Success message after appointment
$appointmentMessage = isset($_SESSION['appointment_success_message']) ? $_SESSION['appointment_success_message'] : null;
// Dismiss message handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dismiss_message'])) {
    $_SESSION['appointment_message_dismissed'] = true;
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hospital Dashboard</title>
</head>
<body>
<header>
    Hospital Dashboard – Blood Bank
</header>
<div>
    <h2>Welcome, <?= htmlspecialchars($hospitalName) ?>!</h2>
    <p>"Saving lives starts with you. Thank you for being the bridge between donors and those in need."</p>
</div>
<?php if ($appointmentMessage && !isset($_SESSION['appointment_message_dismissed'])): ?>
    <div>
        <?= htmlspecialchars($appointmentMessage) ?>
        <form method="post">
            <button type="submit" name="dismiss_message">Dismiss</button>
        </form>
    </div>
<?php endif; ?>
<div>
    <div>
        <!-- Request Blood -->
        <div>
            <form action="your_request.php">
                <button type="submit">Request Blood from Admin</button>
            </form>
        </div>
        <!-- View All Stocks -->
        <div>
            <form action="view_all_stocks.php">
                <button type="submit">Add Blood Stocks</button>
            </form>
        </div>
        <!-- Appointment Dates -->
        <div>
            <form action="appoint.php">
                <button type="submit">Upcoming Appointment Dates</button>
            </form>
        </div>
    </div>
</div>
<footer>
    &copy; 2025 Life Saver Blood Bank. All rights reserved.
</footer>
</body>
</html>


your_request.php
<?php
session_start();
// Database connection
$conn = new mysqli("localhost", "root", "", "login");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Ensure hospital name is set
$hospitalName = isset($_SESSION['hospital_name']) ? $_SESSION['hospital_name'] : 'Hospital Partner';
// Blood groups array
$blood_groups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blood_group = $_POST['blood_group'];
    // Insert into blood_requests table
    $stmt = $conn->prepare("INSERT INTO blood_requests (hospital_name, blood_group, status) VALUES (?, ?, 'pending')");
    $stmt->bind_param("ss", $hospitalName, $blood_group);
    if ($stmt->execute()) {
        $_SESSION['request_id'] = $stmt->insert_id; // Save the request ID
        header("Location: request_status.php");     // Redirect to status page
        exit();
    } else {
        $_SESSION['message'] = "There was an error while submitting your request.";
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Blood Request</title>
</head>
<body>
  <div>
    <h2>🩸 Blood Request Form</h2>
    <?php if (isset($_SESSION['message'])): ?>
      <div>
        <?= $_SESSION['message']; unset($_SESSION['message']); ?>
      </div>
    <?php endif; ?>
    <form action="your_request.php" method="POST">
      <!-- Hospital Name Display -->
      <div>
        <label>🏥 Hospital:</label>
        <input type="text" name="hospital" value="<?= htmlspecialchars($hospitalName); ?>" readonly />
      </div>
      <!-- Blood Group Dropdown -->
      <div>
        <label for="blood_group">🧪 Select Blood Group:</label>
        <select name="blood_group" id="blood_group" required>
          <option value="">-- Select Blood Group --</option>
          <?php foreach ($blood_groups as $group): ?>
            <option value="<?= $group; ?>"><?= $group; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <!-- Submit Button -->
      <div>
        <input type="submit" value="Submit Request">
      </div>
    </form>
    <!-- Back Button -->
    <div>
      <a href="dashboard.php">← Back to Dashboard</a>
    </div>
  </div>
</body>
</html>

request_status.php
<?php
session_start();
$conn = new mysqli("localhost", "root", "", "login");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$requestId = $_SESSION['request_id'] ?? null;
$message = "⚠️ Something went wrong. Please verify your request.";
$debugInfo = "";
if ($requestId) {
    $debugInfo .= "DEBUG: Request ID = " . $requestId . "<br>"
    $stmt = $conn->prepare("SELECT status FROM blood_requests WHERE id = ?");
    $stmt->bind_param("i", $requestId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $debugInfo .= "DEBUG: Found row in blood_requests<br>";
        $status = $row['status'];
        $debugInfo .= "DEBUG: Status = " . $status . "<br>";
        switch ($status) {
            case 'pending':
                $message = "⏳ Your blood request is pending.";
                break;
            case 'accepted':
                $message = "✅ Your blood request has been accepted.";
                break;
            case 'rejected':
                $message = "❌ Due to low stocks, your request has been rejected.";
                break;
            default:
                $message = "⚠️ Unknown status: " . htmlspecialchars($status);
                break;
        }
    } else {
        $debugInfo .= "DEBUG: No row found in blood_requests for this request ID<br>";
    }
    $stmt->close();
} else {
    $debugInfo .= "DEBUG: No request ID in session<br>";
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Status</title>
</head>
<body>
<div>
    <div>
        <?php echo $message; ?>
    </div>
    <a href="need.php">⬅ Go Back to Request Page</a>
    <div>
        <?php echo $debugInfo; ?>
    </div>
</div>
</body>
</html>

view_all_stocks.php
<?php
session_start(); // Start the session to use session variables
// Simulated login session for testing (REMOVE THIS in production)
if (!isset($_SESSION['hospital_name'])) {
    $_SESSION['hospital_name'] = 'CityCare Hospital'; // Replace with actual session logic
}
$hospitalName = $_SESSION['hospital_name'];
// Database configuration
$host = 'localhost';
$dbname = 'login';
$username = 'root';
$password = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
// Handle insert new blood stock
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['blood_group_insert'], $_POST['units'])) {
    $bloodGroupInsert = $_POST['blood_group_insert'];
    $units = $_POST['units'];
    $insertQuery = "INSERT INTO blood_stock (hospital_name, blood_group, units) 
                    VALUES (:hospital_name, :blood_group, :units)";
    $stmt = $pdo->prepare($insertQuery);
    $stmt->bindParam(':hospital_name', $hospitalName);
    $stmt->bindParam(':blood_group', $bloodGroupInsert);
    $stmt->bindParam(':units', $units);
    if ($stmt->execute()) {
        header("Location: blood_stock_graph.php");
        exit;
    } else {
        echo "<div>Failed to add blood stock.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Blood Stock</title>
</head>
<body>
<div>
    <h2>Add Blood Stock</h2>
    <form method="POST">
        <div>
            <label for="hospital_name">Hospital Name</label>
            <input type="text" id="hospital_name" name="hospital_name"
                   value="<?= htmlspecialchars($hospitalName) ?>" readonly>
        </div>
        <div>
            <label for="blood_group_insert">Blood Group</label>
            <select id="blood_group_insert" name="blood_group_insert" required>
                <option value="" disabled selected>Select Blood Group</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
            </select>
        </div>
        <div>
            <label for="units">Units</label>
            <input type="number" id="units" name="units" required min="1">
        </div>
        <button type="submit">Add Blood Stock</button>
    </form>
</div>
</body>
</html>

appoint.php
<?php
session_start();
// Make sure the hospital is logged in
if (!isset($_SESSION['hospital_logged_in']) || !isset($_SESSION['hospital_name'])) {
    header("Location: login.php");
    exit();
}
$loggedInHospital = $_SESSION['hospital_name'];
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hospital = $_POST['hospital'] ?? '';
    $appointment_date = $_POST['appointment_date'] ?? '';
    $state = $_POST['state'] ?? '';
    $city = $_POST['city'] ?? '';
    $address = $_POST['address'] ?? '';
    if (!empty($hospital) && !empty($appointment_date) && !empty($state) && !empty($city) && !empty($address)) {
        $host = "localhost";
        $user = "root";
        $pass = "";
        $dbname = "login";
        $conn = new mysqli($host, $user, $pass, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $stmt = $conn->prepare("INSERT INTO appointments (hospital_name, appointment_date, state, city, address) VALUES (?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die("SQL error: " . $conn->error);
        }
        $stmt->bind_param("sssss", $hospital, $appointment_date, $state, $city, $address);
        if ($stmt->execute()) {
            $_SESSION['appointment_success_message'] = 'Appointment booked successfully on ' . date('F j, Y', strtotime($appointment_date)) . '!';
            echo "
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Appointment Success</title>
                    <meta http-equiv='refresh' content='4;url=hosp.php'>
                    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
                </head>
                <body>
                    <div>
                        Appointment booked successfully! Redirecting to dashboard in a few seconds...
                    </div>
                </body>
                </html>
            exit;
        } else {
            echo "<script>alert('Failed to book appointment. Please try again later.'); window.history.back();</script>";
            exit;
        }
        $stmt->close();
        $conn->close();
    } else {
        echo "<script>alert('Missing information!'); window.history.back();</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hospital Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div>
    <h2>Book Hospital Appointment</h2>
    <form action="appoint.php" method="POST">
        <!-- Hospital Name -->
        <div>
            <label>Hospital Name</label>
            <input type="text" value="<?= htmlspecialchars($loggedInHospital) ?>" readonly>
            <input type="hidden" name="hospital" value="<?= htmlspecialchars($loggedInHospital) ?>">
        </div>
        <!-- State -->
        <div>
            <label>State</label>
            <select name="state" required>
                <option value="">Select State</option>
                <option value="Karnataka">Karnataka</option>
                <option value="Maharashtra">Maharashtra</option>
                <option value="Delhi">Delhi</option>
            </select>
        </div>
        <!-- City -->
        <div>
            <label>City</label>
            <select name="city" required>
                <option value="">Select City</option>
                <option value="Bangalore">Bangalore</option>
                <option value="Mumbai">Mumbai</option>
                <option value="Delhi">Delhi</option>
            </select>
        </div>
        <!-- Address -->
        <div>
            <label>Hospital Address</label>
            <input type="text" name="address" required>
        </div>
        <!-- Date -->
        <div>
            <label>Select Date</label>
            <input type="date" name="appointment_date" required min="<?= date('Y-m-d'); ?>">
        </div>
        <!-- Submit -->
        <div>
            <button type="submit">Submit Appointment</button>
        </div>
    </form>
</div>
</body>
</html>

blood_stock_graph.php
<?php
// Directly establish the connection in this file
// Database configuration
$host = 'localhost';       // Database host
$dbname = 'login';         // Your database name
$username = 'root';        // Your database username
$password = '';            // Your database password
// Create a connection using PDO (PHP Data Objects)
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
// Query for blood stock data
$query = "SELECT blood_group, SUM(units) as total_units FROM blood_stock GROUP BY blood_group";
$stmt = $pdo->prepare($query);
$stmt->execute();
$bloodStocks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blood Stock Graphs</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2>Blood Stock Charts</h2>
    <!-- Pie Chart -->
    <canvas id="pieChart"></canvas>
    <!-- Bar Chart -->
    <canvas id="barChart"></canvas>
    <!-- Alert if units are below 3 -->
    <?php foreach ($bloodStocks as $stock): ?>
        <?php if ($stock['total_units'] < 3): ?>
            <div class="alert alert-danger mt-3">
                <strong>Alert:</strong> The blood group <?= htmlspecialchars($stock['blood_group']) ?> has less than 3 units available.
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
<script>
    // Pie Chart
    const pieData = {
        labels: <?php echo json_encode(array_column($bloodStocks, 'blood_group')); ?>,
        datasets: [{
            label: 'Total Units of Blood',
            data: <?php echo json_encode(array_column($bloodStocks, 'total_units')); ?>,
            backgroundColor: ['#ff9999', '#66b3ff', '#99ff99', '#ffcc99', '#c2c2f0', '#ffb3e6', '#ff6666', '#c2f0c2']
        }]
    };
    const pieConfig = {
        type: 'pie',
        data: pieData
    };
    const pieChart = new Chart(document.getElementById('pieChart'), pieConfig);
    // Bar Chart
    const barData = {
        labels: <?php echo json_encode(array_column($bloodStocks, 'blood_group')); ?>,
        datasets: [{
            label: 'Total Units of Blood',
            data: <?php echo json_encode(array_column($bloodStocks, 'total_units')); ?>,
            backgroundColor: '#66b3ff'
        }]
    };
    const barConfig = {
        type: 'bar',
        data: barData
    };
    const barChart = new Chart(document.getElementById('barChart'), barConfig);
</script>
</body>
</html>
