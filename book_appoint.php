<!-- book_appoint.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Book Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow p-4">
            <h2 class="mb-4 text-center text-primary">Select Your Location</h2>
            <form method="POST" action="view_appointments.php">

                <div class="mb-3">
                    <label for="state" class="form-label">State</label>
                    <select class="form-select" id="state" name="state" required>
                        <option value="" disabled selected>Choose a state</option>
                        <option value="Tamil Nadu">Tamil Nadu</option>
                        <option value="Karnataka">Karnataka</option>
                        <!-- Add more states as needed -->
                    </select>
                </div>
                <div class="mb-3">
                    <label for="city" class="form-label">City</label>
                    <select class="form-select" id="city" name="city" required>
                        <option value="" disabled selected>Choose a city</option>
                        <option value="Chennai">Chennai</option>
                        <option value="Bangalore">Bangalore</option>
                        <!-- Add more cities as needed -->
                    </select>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">View Appointments</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
