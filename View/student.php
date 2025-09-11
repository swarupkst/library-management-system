<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // যদি লগইন না করা থাকে তাহলে ফেরত পাঠাবে
    exit();
}

echo "<h2>This is Student Dashbord!</h2> <br>";
require_once("../Model/database.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
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