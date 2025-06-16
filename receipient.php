<?php
session_start();

$host   = "localhost";
$user   = "root";
$pass   = "";
$dbname = "login";
$conn   = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$success = false;

function validate_name(string $name): bool {
    return (bool) preg_match('/^[A-Za-z][A-Za-z\s]{1,59}$/', $name);
}
function validate_age(int $age): bool { return $age >= 18 && $age <= 65; }
function validate_gender(string $g): bool { return in_array($g, ['Male','Female','Other'], true); }
function validate_phone(string $p): bool { return (bool) preg_match('/^[6-9]\d{9}$/', $p); }
function validate_email(string $e): bool { return filter_var($e, FILTER_VALIDATE_EMAIL) !== false; }
function validate_username(string $u): bool { return (bool) preg_match('/^[A-Za-z0-9_]{5,20}$/', $u); }
function validate_password(string $pw): bool { return (bool) preg_match('/^(?=.*[A-Za-z])(?=.*\d).{6,}$/', $pw); }



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $age      = (int) ($_POST['age'] ?? 0);
    $gender   = $_POST['gender'] ?? '';
    $phone    = preg_replace('/\s+/', '', $_POST['phone'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $address  = trim($_POST['address'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $errors = [];
    if (!validate_name($name))         $errors[] = "Name: letters & spaces (2‑60 chars).";
    if (!validate_age($age))           $errors[] = "Age must be 18‑65.";
    if (!validate_gender($gender))     $errors[] = "Select a gender.";
    if (!validate_phone($phone))       $errors[] = "Phone: 10‑digit Indian mobile starting 6‑9.";
    if (!validate_email($email))       $errors[] = "Invalid email.";
    if (!validate_username($username)) $errors[] = "Username: 5‑20 letters/numbers/_ .";
    if (!validate_password($password)) $errors[] = "Password ≥6 chars with letters & numbers.";
    if (empty($address))               $errors[] = "Address cannot be empty.";

    if ($errors) {
        $message = "❌ " . implode("<br>❌ ", $errors);
    } else {
        $dup_stmt = $conn->prepare(
            "SELECT username, phone, email
             FROM receipient
             WHERE username = ? OR phone = ? OR email = ?
             LIMIT 1"
        );
        $dup_stmt->bind_param("sss", $username, $phone, $email);
        $dup_stmt->execute();
        $result = $dup_stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $dups = [];
            if ($row['username'] === $username) $dups[] = "username";
            if ($row['phone'] === $phone) $dups[] = "mobile number";
            if ($row['email'] === $email) $dups[] = "email";
            $message = "❌ The " . implode(" and ", $dups) . " already exist. Please choose another.";
        } else {
            $ins_stmt = $conn->prepare(
                "INSERT INTO receipient 
                 (name, age, gender, phone, email, address, username, password)
                 VALUES (?,?,?,?,?,?,?,?)"
            );
            $ins_stmt->bind_param(
                "sissssss",
                $name, $age, $gender, $phone, $email, $address, $username, $password
            );

            if ($ins_stmt->execute()) {
                $_SESSION['username'] = $username;
                $_SESSION['name']     = $name;
                $_SESSION['email']    = $email;
           
                $success = true;
            } else {
                $message = "❌ DB error: " . $ins_stmt->error;
            }
            $ins_stmt->close();
        }
        $dup_stmt->close();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Donor Registration</title>
<style>
body{font-family:'Segoe UI',sans-serif;margin:0;padding:0;background:linear-gradient(90deg,#f3f4f6,#e5e7eb);display:flex;flex-direction:column;min-height:100vh}
.container{background:#fff;padding:40px;width:100%;max-width:540px;margin:50px auto;border-radius:16px;box-shadow:0 8px 24px rgba(0,0,0,.1)}
.logo{text-align:center;font-size:36px;font-weight:700;color:#dc2626;margin-top:30px}
h2{text-align:center;margin-bottom:25px;color:#1f2937}
form{display:flex;flex-direction:column;gap:20px}
label{color:#374151;font-weight:600;margin-bottom:5px;display:block}
input,textarea{width:100%;padding:12px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:15px;background:#f9fafb;transition:.2s}
input:focus,textarea:focus{outline:none;border-color:#dc2626;box-shadow:0 0 0 2px rgba(220,38,38,.2)}
.radio-group{display:flex;gap:20px;padding-top:5px}
.radio-group label{font-weight:500;font-size:15px;display:flex;align-items:center}
input[type=radio]{margin-right:6px}
.submit-btn{margin-top:10px;width:100%;padding:14px;background:#dc2626;color:#fff;font-size:17px;font-weight:700;border:none;border-radius:8px;cursor:pointer;transition:background .3s}
.submit-btn:hover{background:#b91c1c}
.message{padding:12px;margin-bottom:20px;border-radius:8px;font-weight:600;font-size:16px;text-align:center}
.success{background:#d1fae5;color:#065f46}.error{background:#fee2e2;color:#b91c1c}
footer{margin-top:auto;padding:20px;background:#f3f4f6;text-align:center;font-size:14px;color:#6b7280}
</style>
</head>
<body>
<div class="logo">Life Saver Blood Bank</div>
<div class="container">
<h2>Donor Registration</h2>
<?php if($message): ?><div class="message error"><?= $message; ?></div><?php endif; ?>
<?php if($success): ?><div class="message success">✅ Registered successfully! Redirecting…</div><script>setTimeout(()=>location.href='page.php',2500)</script><?php endif; ?>
<form method="POST" id="regForm">
  <div>
    <label for="name">Name</label>
    <input type="text" id="name" name="name" pattern="[A-Za-z\s]{2,60}" title="Letters & spaces (2‑60)" required>
  </div>
  <div>
    <label for="age">Age</label>
    <input type="number" id="age" name="age" min="18" max="65" required>
  </div>
  <div>
    <label>Gender</label>
    <div class="radio-group">
      <label><input type="radio" name="gender" value="Male" required> Male</label>
      <label><input type="radio" name="gender" value="Female"> Female</label>
      <label><input type="radio" name="gender" value="Other"> Other</label>
    </div>
  </div>
  <div>
    <label for="phone">Phone Number</label>
    <input type="tel" id="phone" name="phone" pattern="[6-9][0-9]{9}" maxlength="10" title="10‑digit Indian mobile" required>
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
    <input type="text" id="username" name="username" pattern="[A-Za-z0-9_]{5,20}" title="5‑20 letters, numbers, _" required>
  </div>
  <div>
    <label for="password">Password</label>
    <input type="password" id="password" name="password" pattern="(?=.*[A-Za-z])(?=.*\d).{6,}" title="≥6 chars, include letters & numbers" required>
  </div>
  <button type="submit" class="submit-btn">Submit</button>
</form>
</div>
<footer>
  <p>LEGAL RESTRICTIONS AND TERMS OF USE APPLICABLE TO THIS SITE</p>
  <p>Use of this site signifies your agreement to the terms of use.</p>
  <p>© 2025 Life Saver Blood Bank. All rights reserved.</p>
</footer>
<script>
const form=document.getElementById('regForm');
form.addEventListener('submit',e=>{if(!form.checkValidity()){e.preventDefault();alert('Correct the highlighted fields and try again.');}});
</script>
</body>
</html>
