<?php
session_start();
require_once("../Model/database.php");

// চেক করুন admin/librarian লগইন করেছে কিনা
if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

// যদি POST থেকে id আসে
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // ইউজার ডিলিট কোয়েরি
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // সফল হলে রিডাইরেক্ট
        header("Location: ../view/admin.php?msg=deleted");
        exit();
    } else {
        echo "Error deleting user: " . $conn->error;
    }
} else {
    echo "Invalid request!";
}
?>
