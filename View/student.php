<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once("../Model/database.php");

// ধরলাম student এর নাম users টেবিলে আছে
$student_id = $_SESSION['user_id'];
$sqlUser = "SELECT full_name FROM users WHERE id = $student_id";
$userResult = $conn->query($sqlUser);
$user = $userResult->fetch_assoc();
$student_name = $user['full_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="student.css">
    <script>
        // Simple JS search filter
        function searchBooks() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let rows = document.querySelectorAll("#booksTable tbody tr");
            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(input) ? "" : "none";
            });
        }
    </script>
</head>
<body>
    <!-- Profile Section -->
    <div class="profile-bar">
    <div class="profile-info">
        👤 Welcome, <b><?php echo htmlspecialchars($student_name); ?></b>
    </div>
    <div>
        <a href="profile.php" class="profile-btn">Profile</a>
        <a href="../Controller/logout.php" class="logout-btn">Logout</a>
    </div>
</div>

        <!-- Available Books -->
        <div class="table-box">
            <h2>Available Books</h2>
            <input type="text" id="searchInput" onkeyup="searchBooks()" placeholder="Search by title, author, ISBN...">
            
            <table id="booksTable">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author(s)</th>
                        <th>ISBN</th>
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
                                    <td>{$row['quantity']}</td>
                                    <td>";
                            if ($row['quantity'] > 0) {
                                echo "<form action='../Controller/borrow_book.php' method='POST'>
                                        <input type='hidden' name='book_id' value='{$row['id']}'>
                                        <button type='submit'>Borrow</button>
                                      </form>";
                            } else {
                                echo "<span class='out-stock'>Out of Stock</span>";
                            }
                            echo "</td></tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
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
    </div>
</body>
</html>