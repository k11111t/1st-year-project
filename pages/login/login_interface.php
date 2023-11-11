<?php
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


if ($_POST) {
  $username = $_POST['username'];
  $password = $_POST['password'];
    if($username==""){
      header("Location: login.php?errorID=1");
      die("0");
    }
    if ($password==""){
      header("Location: login.php?errorID=1");
      die("1");
    }
    //select stuff database that will be put into session
    $QueryUsers =  "SELECT pwd, lastlogin, email, forename, surname FROM users WHERE username='$username'";
    $result = mysqli_query($connect, $QueryUsers);
    if($result) {
      $row = mysqli_fetch_assoc($result);
      //user was found, now check for password
        if(password_verify($password,$row["pwd"])) {
          //passwords match, now check for last login
          if($row['lastlogin'] == NULL) {
            //set new last login, this is the first time the user as logged in
            $today = new DateTime();
            $temp = $today->format('Y-m-d-H:i:s');
            $SetLastLogin =  "UPDATE users SET lastlogin='$temp' WHERE username='$username'";
            $result = mysqli_query($connect, $SetLastLogin);
            if($result){
              echo("successfully updated<br>");
            }
            else{
              echo("it did not update<br>");
            }
          }
          //user has the last login already set
          else {
              //calculate the difference between database date and current date
              $today = new DateTime();
              $temp = $today->format('Y-m-d-H:i:s');
              $last_login = $row["lastlogin"];
              $last_login = new DateTime($last_login);
              $interval = $today->diff($last_login);

              $year_difference = $interval->y;
              $month_difference = $interval->m;
              $day_difference = $interval->d;
              $hour_difference = $interval->h;

              //user will get bonus currency after 5 hours
              if(($year_difference != 0) or ($month_difference != 0) or ($day_difference != 0) or ($hour_difference>=5)){
                //fetch balance from the database
                $GetCurrency = "SELECT balance FROM bank WHERE username='$username'";
                $result = mysqli_query($connect, $GetCurrency);
                //the fetch was successful
                if($result){

                  //update balance - will fail if there is no row in table 'bank'
                  $row = mysqli_fetch_assoc($result);
                  $newbalance = $row['balance'] + 50; //adds 50 to the current balance
                  $AddCurrency = "UPDATE bank SET balance=$newbalance WHERE username='$username'";
                  $result = mysqli_query($connect, $AddCurrency);
                    if($result){
                      echo('successfully updated currency<br>');
                    }
                    else{
                      echo("could not update currency<br>");
                    }

                  //update last login - the currency was added, now update the last login
                  $Updatelastlogin = "UPDATE users SET lastlogin='$temp' WHERE username='$username'";
                  $result = mysqli_query($connect, $Updatelastlogin);
                    if($result){
                      echo('the last login was updated successfully<br>');
                    }
                    else{
                      echo('the last login was not updated<br>');
                    }
                }
                else{
                  echo("failed to get balance from database<br>");
                }
              }
              else {
                echo 'do not add money<br>';
              }
          }
		      //user has successfully logged in
          session_start();
          $sessionID = session_create_id();
          $_SESSION['sessionID'] = $sessionID;
          $_SESSION['username'] = $username;
          $UpdateUser = "UPDATE users SET sessionid = '$sessionID' WHERE users.username = '$username'";
          if (mysqli_query($connect, $UpdateUser) == TRUE){
            header("Location: ../home/home.php");
            die("");
          }
          die("error making session id");
        }
        else{
            //password is incorrect
            header("Location: login.php?errorID=1");
            die("Password is incorrect");
        }
    }
    else {
      //user was not found
      header("Location: login.php?errorID=1");
      die("Error: User not found");
    }
}
?>
