<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CSRF Update account email</title>
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

    $drop_query = "DROP TABLE IF EXISTS UserAccounts";
    if ($conn->query($drop_query) !== TRUE) {
        echo "Error dropping table: " . $conn->error;
    }

    $create_query = <<<_CREATE_
        CREATE TABLE IF NOT EXISTS UserAccounts (
          user_id INT(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
          user_name VARCHAR(100) NOT NULL,
          user_email VARCHAR(150) NOT NULL
        )
_CREATE_;

    $result = $conn->query($create_query);
    if($result === FALSE) {
        die("Create failed: " . mysql_error());
    }

  $insert_queries = array();
  $insert_queries[] = <<<_INSERT_
      INSERT INTO UserAccounts
      VALUES
        (1234, 'Tidus', 'tidus@example.com')
_INSERT_;
  $insert_queries[] = <<<_INSERT_
      INSERT INTO UserAccounts
      VALUES
        (1111, 'Nana', 'nana@example.com')
_INSERT_;
  $insert_queries[] = <<<_INSERT_
      INSERT INTO UserAccounts
      VALUES
        (2222, 'Yuna', 'yuna@example.com')
_INSERT_;
  $insert_queries[] = <<<_INSERT_
      INSERT INTO UserAccounts
      VALUES
        (3333, 'Ren', 'ren@example.com')
_INSERT_;

    foreach ($insert_queries as $query) {
      if ($conn->query($query)) {
          //echo "Inserted row: $query\n";
      } else {
          echo "Error inserting row: " . mysqli_error($conn) . "\n";
      }
    }
    return $conn;
  }

  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

  try {
    $conn = getDatabaseConnection();

    //echo "Updating email <br>";
    $user_id = $conn->real_escape_string($_GET['user_id']);
    $new_email = $conn->real_escape_string($_GET['new_email']);

    if (!empty($user_id) && !empty($new_email)) {
      $update_query =
        "UPDATE UserAccounts SET user_email = '$new_email' where user_id = $user_id";
      echo "Email change successful!! <br>";
      $conn->query($update_query);
      echo "User: $user_id, New email: $new_email <br>";

      $conn->close();
    }
  } catch (Exception $e) {
    echo 'Error! ' . $e->getCode();
  }
?>


<h3> Request to change email address</h3>
<form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <p>Update Email</p>
  <input type="text" name="user_id" value="1234">
  <input type="text" name="new_email" placeholder="New email address">
  <input type="submit" value="Change Email">
</form>
</body>
</html>
