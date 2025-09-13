<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once("../Model/database.php");
$student_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../View/student.css">
</head>
<body>
<h2>Available Books</h2>
<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Author(s)</th>
            <th>ISBN</th>
            <th>Summary</th>
            <th>Quantity</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT * FROM books ORDER BY title ASC";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['title']}</td>
                        <td>{$row['author']}</td>
                        <td>{$row['isbn']}</td>
                        <td>{$row['summary']}</td>
                        <td>{$row['quantity']}</td>
                        <td>";
                
                if ($row['quantity'] > 0) {
                    echo "<form action='../Controller/borrow_book.php' method='POST'>
                            <input type='hidden' name='book_id' value='{$row['id']}'>
                            <button type='submit'>Borrow</button>
                          </form>";
                } else {
                    echo "Out of Stock";
                }

                echo "</td></tr>";
            }
        }
        ?>
    </tbody>
</table>

<h2>My Borrowed Books</h2>
<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Author(s)</th>
            <th>Borrow Date</th>
            <th>Due Date</th>
            <th>Return Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT bb.*, b.title, b.author 
                FROM borrowed_books bb 
                JOIN books b ON bb.book_id = b.id 
                WHERE bb.student_id = $student_id 
                ORDER BY bb.borrow_date DESC";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['title']}</td>
                        <td>{$row['author']}</td>
                        <td>{$row['borrow_date']}</td>
                        <td>{$row['due_date']}</td>
                        <td>".($row['return_date'] ?? 'Not Returned')."</td>
                        <td>";
                
                if (is_null($row['return_date'])) {
                    echo "<form action='../Controller/return_book.php' method='POST'>
                            <input type='hidden' name='borrow_id' value='{$row['id']}'>
                            <input type='hidden' name='book_id' value='{$row['book_id']}'>
                            <button type='submit'>Return</button>
                          </form>";
                } else {
                    echo "✔ Returned";
                }

                echo "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No borrowed books yet</td></tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>
