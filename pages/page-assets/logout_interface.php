<?php
  session_start();
  $sessionID = $_SESSION["sessionID"];

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
  //connection successful

  $UpdateUser = "UPDATE users SET sessionid = NULL WHERE users.sessionid = '$sessionID'";
  if (mysqli_query($connect, $UpdateUser) == TRUE){
    session_destroy();
    header("Location: ../login/login.php");
    die("");
  }
  die("error logging out");

 ?>
