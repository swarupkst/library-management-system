<?php
// registration.php

// Start session (ব্যবহার করলে পরবর্তীতে message বা redirect সহজ হবে)
session_start();

// DB credentials - তোমার পরিবেশ অনুযায়ী ঠিক করে নাও
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname     = "library_db";

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Helper for safe echo to HTML
function esc($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

// Initialize variables for sticky form values and messages
$name = $username = $email = $role = "";
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Collect & trim inputs
    $name     = trim($_POST['name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';
    $role     = $_POST['role'] ?? '';

    // Basic validation
    if ($name === "" || $username === "" || $email === "" || $password === "" || $confirm === "" || $role === "") {
        $error = "Must fill the all field";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } elseif ($password !== $confirm) {
        $error = "❌ Passwords do not match!";
    } else {
        // Check for duplicate username or email using prepared statement
        $checkSql = "SELECT username, email FROM users WHERE username = ? OR email = ? LIMIT 1";
        if ($stmt = $conn->prepare($checkSql)) {
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($existingUsername, $existingEmail);
                $stmt->fetch();
                if ($existingUsername === $username) {
                    $error = "❌ Username already taken!";
                } elseif ($existingEmail === $email) {
                    $error = "❌ Email already registered!";
                } else {
                    $error = "Username or Email already exists!";
                }
                $stmt->close();
            } else {
                $stmt->close();
                // No duplicate: proceed to insert
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $insertSql = "INSERT INTO users (full_name, username, email, password, role) VALUES (?, ?, ?, ?, ?)";
                if ($ins = $conn->prepare($insertSql)) {
                    $ins->bind_param("sssss", $name, $username, $email, $hashedPassword, $role);
                    if ($ins->execute()) {
                        $success = "✅ Registration Successful! Now you can <a href='login.php'>Login</a> ";
                        // Clear sticky values on success
                        $name = $username = $email = $role = "";
                    } else {
                        // DB insertion error (rare, but handle)
                        $error = "Database error: " . $ins->error;
                    }
                    $ins->close();
                } else {
                    $error = "Prepare failed: " . $conn->error;
                }
            }
        } else {
            $error = "Prepare failed: " . $conn->error;
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registration Form</title>
  <link rel="stylesheet" href="registration.css">

</head>
<body>

  <div class="form-container">
    <h2>Registration Form</h2>

    <?php if ($error): ?>
      <div class="message error"><?php echo esc($error); ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
      <div class="message success"><?php echo $success; /* contains safe link to login */ ?></div>
    <?php endif; ?>

    <form action="registration.php" method="post" novalidate>
      <div class="form-row">
        <div class="form-group">
          <label for="name">Full Name</label>
          <input type="text" id="name" name="name" required value="<?php echo esc($name); ?>">
        </div>

        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" required value="<?php echo esc($username); ?>">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" required value="<?php echo esc($email); ?>">
        </div>

        <div class="form-group">
          <label for="role">Select Role</label>
          <select id="role" name="role" required>
            <option value="" disabled <?php echo $role=="" ? 'selected' : ''; ?>>-- Select --</option>
            <option value="student" <?php echo $role==='student' ? 'selected' : ''; ?>>Student</option>
            <option value="librarian" <?php echo $role==='librarian' ? 'selected' : ''; ?>>Librarian</option>
            <option value="admin" <?php echo $role==='admin' ? 'selected' : ''; ?>>Admin</option>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
          <label for="confirm_password">Re-type Password</label>
          <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
      </div>

      <button type="submit" class="btn">Register</button>
    </form>

    <div class="link">
      <p>Already have account? <a href="login.php">Login</a></p>
    </div>
  </div>

</body>
</html>
