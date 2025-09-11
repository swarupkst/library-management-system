<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
echo "<h2>This is Admin Dashbord!</h2>";

$username = $_SESSION['username']; 
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Dashboard</title>
</head>
<body>
  <h2>Welcome, <?php echo htmlspecialchars($username); ?> 👋</h2>
  <p>You are logged in as: <b><?php echo ucfirst($role); ?></b></p>

  <a href="logout.php">Logout</a>
  
</body>
</html>