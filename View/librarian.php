<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // যদি লগইন না করা থাকে তাহলে ফেরত পাঠাবে
    exit();
}

require_once("../Model/database.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Library - Add Book</title>
  <link rel="stylesheet" href="librarian.css">
</head>
<body>
  <div class="form-container">
    <h2>Add Book Details</h2>
    <form action="../Controller/save_book.php" method="POST">
      <div class="form-group">
        <label for="title">Book Name</label>
        <input type="text" id="title" name="title" placeholder="Enter full book name" required>
      </div>
      <div class="form-group">
        <label for="author">Author(s)</label>
        <input type="text" id="author" name="author" placeholder="Enter author(s) name" required>
      </div>
      <div class="form-group">
        <label for="isbn">ISBN/ISSN</label>
        <input type="text" id="isbn" name="isbn" placeholder="Enter ISBN/ISSN number" required>
      </div>
      <div class="form-group">
        <label for="summary">Summary / Description</label>
        <textarea id="summary" name="summary" placeholder="Write a short overview..." required></textarea>
      </div>
      <button type="submit">Save Book</button>
    </form>
  </div>

  <table>
    <thead>
      <tr>
        <th>Title</th>
        <th>Author(s)</th>
        <th>ISBN/ISSN</th>
        <th>Summary</th>
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
                  </tr>";
          }
        }
      ?>
    </tbody>
  </table>
</body>
</html>
