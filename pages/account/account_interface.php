<?php
  $username = "N/A";
  $email = "N/A";
  $forename = "N/A";
  $surname = "N/A";

  if (isset($_SESSION['username'])) {
    $servername = 'localhost';
    $databaseusername = 'root';
    $databasepassword = '';
    $database = '2019_comp10120_y8';

    // connection
    $connect = mysqli_connect($servername, $databaseusername, $databasepassword, $database);
    if(!$connect)
    {
      die('Connection failed: ' . mysqli_connect_error()); //connection unsuccessful
    }
    //connection successful
    $sessionUser = $_SESSION['username'];
    $QueryUsers = "SELECT users.username, users.email, users.forename, users.surname
                   FROM users
                   WHERE username = '$sessionUser'";

    $result = mysqli_query($connect, $QueryUsers);
    if($result) {
      $row = mysqli_fetch_assoc($result);

      if ($row["username"]) $username = $row["username"];
      else $username = "NULL";

      if ($row["email"]) $email = $row["email"];
      else $email = "NULL";

      if ($row["forename"]) $forename = $row["forename"];
      else $forename = "NULL";

      if ($row["surname"]) $surname = $row["surname"];
      else $surname = "NULL";
    }
  }
 ?>
