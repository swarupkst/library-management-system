<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once("../Model/database.php");

$student_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = $student_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <link rel="stylesheet" href="student.css">
</head>
<body>
    <div class="profile-page">
        <h2>My Profile</h2>
        <form action="../Controller/update_profile.php" method="POST">
            <label>Name</label>
            <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>

            <label>New Password (leave blank if no change)</label>
            <input type="password" name="password" placeholder="Enter new password">

            <button type="submit">Update Profile</button>
        </form>

        <a href="../View/student.php" class="back-btn">⬅ Back to Dashboard</a>
    </div>
</body>
</html>
