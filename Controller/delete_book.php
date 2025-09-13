<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../View/login.php");
    exit();
}

require_once("../Model/database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']); 

    $sql = "DELETE FROM books WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../View/librarian.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>
