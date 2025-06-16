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

    // Reset donation count
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

    // Increment donation count (optional)
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
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(to right, #ff4d4d, #b30000);
        color: white;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        flex-direction: column;
    }
    .reward-card {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        padding: 30px 40px;
        border-radius: 20px;
        max-width: 500px;
        width: 90%;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    }
    .reward-card h2 {
        margin-bottom: 10px;
    }
    .progress-container {
        background: rgba(255,255,255,0.2);
        height: 25px;
        border-radius: 20px;
        overflow: hidden;
        margin: 20px 0;
    }
    .progress-bar {
        height: 100%;
        background: #00ff99;
        width: <?php echo $progressPercent; ?>%;
        transition: width 0.5s ease-in-out;
    }
    .count {
        font-size: 20px;
        margin-top: 10px;
    }
    .reward {
        margin-top: 20px;
        padding: 15px;
        border-radius: 15px;
        background: <?php echo $rewardUnlocked ? '#00cc66' : '#ffcccb'; ?>;
        color: <?php echo $rewardUnlocked ? 'white' : '#660000'; ?>;
        font-weight: bold;
        font-size: 18px;
    }
    .reward-form {
        display: <?php echo $rewardUnlocked ? 'block' : 'none'; ?>;
        margin-top: 30px;
        padding: 15px;
        background: rgba(255,255,255,0.2);
        border-radius: 15px;
    }
    .back-btn {
        margin-top: 30px;
        display: inline-block;
        padding: 10px 20px;
        background: rgba(255,255,255,0.2);
        color: white;
        text-decoration: none;
        border-radius: 10px;
        border: 2px solid white;
        font-weight: bold;
        transition: background 0.3s;
        cursor: pointer;
    }
    .back-btn:hover {
        background: rgba(255,255,255,0.4);
    }
    select {
        padding: 10px;
        font-size: 16px;
        background-color: #fff;
        color: #333;
        border: 2px solid #ccc;
        border-radius: 10px;
        width: 200px;
        margin: 10px 0;
        transition: all 0.3s ease;
    }
    select:focus {
        border-color: #00ff99;
        outline: none;
    }
</style>
</head>
<body>
    <div class="reward-card">
        <h2>🎁 Your Donation Journey</h2>
        <p class="count">You've donated <strong><?php echo $donationCount; ?></strong> time<?php echo $donationCount == 1 ? '' : 's'; ?>!</p>

        <div class="progress-container">
            <div class="progress-bar"></div>
        </div>
        <p><strong>Goal:</strong> 4 Donations</p>

        <div class="reward" id="reward-message">
            <?php if ($rewardUnlocked): ?>
                🎉 Congratulations! You've earned <strong>25% OFF</strong> on a full body checkup at our partnered hospital!
            <?php else: ?>
                Keep going! Donate <?php echo $goal - $donationCount; ?> more time<?php echo ($goal - $donationCount) == 1 ? '' : 's'; ?> to unlock your reward!
            <?php endif; ?>
        </div>

        <div class="reward-form" id="reward-form" style="display: <?php echo $rewardUnlocked ? 'block' : 'none'; ?>;">
            <form id="donationForm" action="hospital_info.php" method="POST">
                <label for="state">Select State:</label><br />
                <select name="state" id="state" required>
                    <option value="">Select a state</option>
                    <?php foreach ($statesCities as $state => $cities): ?>
                        <option value="<?php echo htmlspecialchars($state); ?>"><?php echo htmlspecialchars($state); ?></option>
                    <?php endforeach; ?>
                </select>
                <br><br>

                <label for="city">Select City:</label><br />
                <select name="city" id="city" required>
                    <option value="">Select a city</option>
                </select>
                <br><br>

                <button type="submit" class="back-btn">Submit</button>
            </form>
        </div>

        <button id="resetButton" class="back-btn" style="background: #ff6666; margin-top: 10px;">🔄 Reset Donations</button>

        <a href="dashboard.php" class="back-btn" style="display: inline-block; margin-top: 20px;">← Back to Dashboard</a>
    </div>

<script>
    const statesCities = <?php echo json_encode($statesCities); ?>;
    const stateSelect = document.getElementById('state');
    const citySelect = document.getElementById('city');
    const rewardMessage = document.getElementById('reward-message');
    const rewardForm = document.getElementById('reward-form');
    const resetButton = document.getElementById('resetButton');
    const progressBar = document.querySelector('.progress-bar');
    const countText = document.querySelector('.count');
    const goal = <?php echo $goal; ?>;

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

    resetButton.addEventListener('click', () => {
        fetch('', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'reset=true'
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const newCount = data.donationCount;
                const unlocked = data.rewardUnlocked;

                countText.innerHTML = `You've donated <strong>${newCount}</strong> time${newCount === 1 ? '' : 's'}!`;
                progressBar.style.width = `${Math.min((newCount / goal) * 100, 100)}%`;

                if (unlocked) {
                    rewardMessage.innerHTML = `🎉 Congratulations! You've earned <strong>25% OFF</strong> on a full body checkup at our partnered hospital!`;
                    rewardForm.style.display = 'block';
                } else {
                    rewardMessage.innerHTML = `Keep going! Donate ${goal - newCount} more time${(goal - newCount) === 1 ? '' : 's'} to unlock your reward!`;
                    rewardForm.style.display = 'none';
                }
            } else {
                alert('Failed to reset donation count.');
            }
        })
        .catch(err => alert('Error: ' + err));
    });
</script>
</body>
</html>
