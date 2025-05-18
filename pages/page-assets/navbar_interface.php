<?php
  session_start();
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
    $QueryUsers = "SELECT users.username, bank.balance
                   FROM users LEFT JOIN bank ON bank.username = users.username
                   WHERE users.username='$sessionUser'";

    $result = mysqli_query($connect, $QueryUsers);
    if($result) {
      $row = mysqli_fetch_assoc($result);

      if ($row["username"]) $username = $row["username"];
      else $username = "NULL";

      if (isset($row["balance"])) $balance = $row["balance"];
      else $balance = "NULL";
    }
  }
  else {
    $username = "N/A";
    $balance = "N/A";
  }
 ?>
