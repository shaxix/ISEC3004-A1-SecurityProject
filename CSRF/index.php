<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CSRF Accounts Index</title>
<style> 
body { margin: 40px; } 
form { max-width: 300px; }
form, input, form p { margin-bottom: 15px; display: block; width: 100%; box-sizing: border-box; padding: 6px; }
input[type="submit"] {width: auto; padding: 8px 16px;}
</style>
</head>
<body style="font-family: sans-serif;">

<?php
  function getDatabaseConnection() {
    $servername = "localhost";
    $username = "admin";
    $password = "admin";
    $dbName = "testDB";

    $conn = new mysqli($servername, $username, $password, $dbName);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
  }

  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
      $conn = getDatabaseConnection();
      $query = "SELECT * FROM UserAccounts";
      echo "<h3>User Accounts Index</h3>";

      $result = $conn->query($query);

      if ($result->num_rows > 0) {
          echo '<br><br><table>';
          echo '<td style="width: 100px; height: 22px">' . "<b>User ID</b>" . '</td>';
          echo '<td style="width: 150px; height: 22px">' . "<b>Username</b>" . '</td>';
          echo '<td style="width: 220px; height: 22px">' . "<b>Email</b>" . '</td>';
          while($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td style="width: 100px; height: 18px">' . $row['user_id'] . '</td>';
            echo '<td style="width: 150px; height: 18px">' . $row['user_name'] . '</td>';
            echo '<td style="width: 220px; height: 18px">' . $row['user_email'] . '</td>';
            echo '</tr>';
          }
          echo '</table>';
      } else {
          echo "<br><br>No results match your search:-(";
      }


      $conn->close($conn);
    } catch (Exception $e) {
      echo 'Error! ' + $e->getCode();
    }
  
?>

<h3> Request to change email address</h3>
<form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
   <br>
  <input type="submit" value="See all account details">
</form>
<br>
</body>
</html>
