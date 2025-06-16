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
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #ff4d4d, #b30000);
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            color: white;
            width: 400px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.4);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
            text-align: left;
        }

        select, button {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border-radius: 8px;
            border: none;
            font-size: 16px;
        }

      select {
    background-color: rgba(255, 255, 255, 0.2);
    color: #fff; /* Keeps white in the main dropdown */
}

/* Style the actual dropdown options */
select option {
    color: #000; /* Black text for better visibility in dropdown list */
    background-color: #fff; /* White background for contrast */
}

        }

        button {
            background-color: #ffffff;
            color: #b30000;
            font-weight: bold;
            margin-top: 20px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #ffe0e0;
        }
    </style>
</head>
<body>
    <div class="form-container">
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
