
<?php
require_once("../Model/database.php");



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title   = $_POST['title'];
    $author  = $_POST['author'];
    $isbn    = $_POST['isbn'];
    $summary = $_POST['summary'];

    $sql = "INSERT INTO books (title, author, isbn, summary) 
            VALUES ('$title', '$author', '$isbn', '$summary')";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../view/librarian.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

