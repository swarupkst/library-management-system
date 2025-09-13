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
    <link rel="stylesheet" href="../view/profile.css">
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

    <!-- My Borrowed Books -->
    <div class="table-box">
        <h2>My Borrowed Books</h2>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Borrow Date</th>
                    <th>Due Date</th>
                    <th>Return Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT bb.*, b.title 
                        FROM borrowed_books bb 
                        JOIN books b ON bb.book_id = b.id 
                        WHERE bb.student_id = $student_id 
                        ORDER BY bb.borrow_date DESC";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $rowClass = '';
                        if (is_null($row['return_date']) && strtotime($row['due_date']) < time()) {
                            $rowClass = "class='overdue'";
                        }
                        echo "<tr $rowClass>
                                <td>{$row['title']}</td>
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
                    echo "<tr><td colspan='5'>No borrowed books yet</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>
