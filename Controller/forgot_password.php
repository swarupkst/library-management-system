<?php
session_start();
require_once("../Model/Database.php");

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // ইউজার আছে কিনা চেক করো
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // টোকেন বানাও
        $token = bin2hex(random_bytes(50));
        $expire = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // ডাটাবেসে টোকেন সেভ
        $update = "UPDATE users SET reset_token='$token', token_expire='$expire' WHERE email='$email'";
        $conn->query($update);

        // Reset link বানাও
        $reset_link = "../Controller/reset_password.php?token=" . $token;

        // মেইল পাঠানো
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
        $mail->Host       = 'smtp.hostinger.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'portfolio@swarupkst.com'; 
        $mail->Password   = '?Xn!Ln+8Pp>'; 
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;


            $mail->setFrom("portfolio@swarupkst.com", "Library System");
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = "Password Reset Request";
            $mail->Body    = "Click this link to reset your password: <a href='$reset_link'>$reset_link</a>";

            $mail->send();
            echo "✅ Password reset link sent to your email!";
        } catch (Exception $e) {
            echo "❌ Message could not be sent. Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "❌ No account found with this email!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password</title>
</head>
<body>
  <h2>Forgot Password</h2>
  <form method="post">
      <label>Enter your email:</label>
      <input type="email" name="email" required>
      <button type="submit">Send Reset Link</button>
  </form>
</body>
</html>
