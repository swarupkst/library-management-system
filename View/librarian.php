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
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f6f1ee;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 20px;
    }
    .form-container {
      background: #fff;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      width: 400px;
      margin-bottom: 30px;
    }
    .form-container h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #4f4a45;
    }
    .form-group { margin-bottom: 15px; }
    label {
      display: block;
      margin-bottom: 6px;
      font-weight: bold;
      color: #4f4a45;
    }
    input, textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
    }
    textarea {
      resize: vertical;
      min-height: 80px;
    }
    button {
      width: 100%;
      padding: 12px;
      background: #4f4a45;
      color: #fff;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 16px;
    }
    button:hover { background: #333; }
    table {
      width: 90%;
      border-collapse: collapse;
      margin-top: 20px;
      background: #fff;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    th, td {
      border: 1px solid #ddd;
      padding: 12px;
      text-align: left;
      font-size: 14px;
    }
    th {
      background: #4f4a45;
      color: #fff;
    }
  </style>
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

  <!-- Table for showing added books -->
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
