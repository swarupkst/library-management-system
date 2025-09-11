<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Form</title>
  <link rel="stylesheet" href="registration.css">
</head>
<body>

  <div class="form-container">

  <?php
// Database connection
$servername = "localhost";
$username   = "root";      // XAMPP এর ডিফল্ট
$password   = "";          // XAMPP এ সাধারনত খালি থাকে
$dbname     = "login_registration";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = $_POST['name'];
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];
    $role     = $_POST['role'];

    // Password match check
    if ($password !== $confirm) {
        echo "❌ Passwords do not match!";
        exit;
    }

    // Secure password hash
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert query
    $sql = "INSERT INTO users (full_name, username, email, password, role) 
            VALUES ('$name', '$username', '$email', '$hashedPassword', '$role')";

    if ($conn->query($sql) === TRUE) {
        // Prevent resubmission with redirect
        //header("Location: library-management-system/controller/success.php");
        
echo "<h2> Registration Successful!</h2>";
echo "<a href='login.php'>Login Now</a>";
echo "<a href='registration.php'>Go Back to Registration</a>";


        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

    <h2>Registration Form</h2>
    <form action="registration.php" method="post">

      <div class="form-row">
        <div class="form-group">
          <label for="name">Full Name</label>
          <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
          <label for="role">Select Role</label>
          <select id="role" name="role" required>
            <option value="" disabled selected>-- Select --</option>
            <option value="student">Student</option>
            <option value="librarian">Librarian</option>
            <option value="admin">Admin</option>
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
           
           <p> Already have account ?  <a href="login.php">Login</a></p>
        </div>
  </div>


</body>
</html>
