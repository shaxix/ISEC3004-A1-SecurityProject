<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Database</title>
</head>
</html>

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
          user_email VARCHAR(150) NOT NULL,
          user_password VARCHAR(100) NOT NULL
        )
_CREATE_;

    $result = $conn->query($create_query);
    if($result === FALSE) {
        die("Create failed: " . mysql_error());
    }

  $insert_queries = array();
  $insert_queries[] = <<<_INSERT_
      INSERT INTO UserAccounts (user_id, user_name, user_email, user_password)
      VALUES
        (1234, 'Tidus', 'tidus@example.com', '1234')
_INSERT_;
  $insert_queries[] = <<<_INSERT_
      INSERT INTO UserAccounts (user_id, user_name, user_email, user_password)
      VALUES
        (2222, 'Yuna', 'yuna@example.com', '1234')
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
      echo "<span style=\"color: green;\">Email change successful!!</span> <br>";
      $conn->query($update_query);
      echo "User: $user_id, New email: $new_email <br>";

      $conn->close();
    }
  } catch (Exception $e) {
    echo 'Error! ' . $e->getCode();
  }
?>

