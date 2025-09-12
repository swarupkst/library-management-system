<?php
require_once("db_connect.php");

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $sql = "SELECT * FROM users WHERE reset_token='$token' AND token_expire > NOW()";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $conn->query("UPDATE users SET password='$new_password', reset_token=NULL, token_expire=NULL WHERE reset_token='$token'");
            echo "✅ Password changed successfully! <a href='login.php'>Login</a>";
            exit();
        }
    } else {
        echo "❌ Invalid or expired token.";
        exit();
    }
}
?>

<form method="post">
    <label>New Password:</label>
    <input type="password" name="password" required>
    <button type="submit">Reset Password</button>
</form>
