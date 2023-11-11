<?php
  session_start();

  if (isset($_SESSION['sessionID']) and isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    $servername = 'dbhost.cs.man.ac.uk';
    $databaseusername = 'h21817ja';
    $databasepassword = 'dbp455wrd';
    $database = '2019_comp10120_y8';

    // connection
    $connect = mysqli_connect($servername, $databaseusername, $databasepassword, $database);
    if(!$connect)
     {
       die('Connection failed: ' . mysqli_connect_error()); //connection unsuccessful
     }

     $QuerySession = "SELECT users.sessionid FROM users WHERE users.username = '$username'";
     $result = mysqli_query($connect, $QuerySession);
     if($result) {
       $row = mysqli_fetch_assoc($result);
       if ($row["sessionid"] != $_SESSION['sessionID']) {
         header("Location: ../welcome/welcome.php");
         die("");
       }
     }
  }
  else {
    header("Location: ../welcome/welcome.php");
    die("");
  }
?>
