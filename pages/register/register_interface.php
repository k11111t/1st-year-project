<?php
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

/*
ERROR MESSAGE ID TABLE:
0 - NO Forename
1 - NO Surname
2 - NO Username
3 - NO Email
4 - NO Password
5 - Illegal Characters in Forename
6 - Illegal Characters in Surname
7 - Illegal Characters in Username
8 - Illegal Characters in Email
9 - Illegal Characters in Password
10 - Password failed criteria
11 - Non-Unique Username
12 - Non-Unique Email
13 - Passwords don't match
14 - NO consent
*/

if ($_POST) {
  if (!isset($_POST["doesAgree"])) {
    header("Location: register.php?errorID=14");
    die("");
  }
  //setting up form variables
	$username = $_POST["username"];
	$password = $_POST["password"];
	$password2 = $_POST["repassword"];
	$email = $_POST["email"];
	$forename = $_POST["forename"];
	$surname = $_POST["surname"];

  //check if all set
  if ($forename == "") {
    header("Location: register.php?errorID=0");
    die("");
  }
  if ($surname == "") {
    header("Location: register.php?errorID=1");
    die("");
  }
	if ($username == "") {
    header("Location: register.php?errorID=2");
    die("");
  }
  if ($email == "") {
    header("Location: register.php?errorID=3");
    die("");
  }
  if ($password == "" || $password == "") {
    header("Location: register.php?errorID=4");
    die("");
  }


  if (isset($username) and isset($password) and isset($password2) and isset($email) and isset($forename)and isset($surname))
  {
    //password regex check
    $passValid = (preg_match('/[A-Z]/', $password) and preg_match('/[a-z]/', $password) and preg_match('/\d/', $password));
    $passValid = ($passValid and (strlen($password)>=8) and (strlen($password)<=20));

    //if password is OK, then check for illegal characters in all the data values
    if($passValid){
      $i = 0;
      $checker_for_chars = [str_split($password), str_split($username), str_split($email), str_split($forename), str_split($surname)];
      $illegal_chars = ["," ,'"', "$", "=", "(", ")", ";", "{", "}", "^", "|", "&"];
      foreach($checker_for_chars as $temp_array){
          foreach($temp_array as $char){
              if (in_array( $char, $illegal_chars ,TRUE )){
                $passValid = FALSE;
                switch($i){
                  case 3: header("Location: register.php?errorID=5");
                          die("");
                          // die('illegal char in forename ' . $char. "\n");
                          break;
                  case 4: header("Location: register.php?errorID=6");
                          die("");
                          // die('illegal char in surname ' . $char. "\n");
                          break;
                  case 1: header("Location: register.php?errorID=7");
                          die("");
                          // die('illegal char in username ' . $char. "\n");
                          break;
                  case 2: header("Location: register.php?errorID=8");
                          die("");
                          // die('illegal char in email ' . $char. "\n");
                          break;
                  case 0: header("Location: register.php?errorID=9");
                          die("");
                          // die( 'illegal char in password ' . $char. "\n");
                          break;

                }
              }
          }
          $i = $i +1;
      }
    }
    else {
      header("Location: register.php?errorID=10");
      die("");
      // die("Password does not meet specifications."); //change this to ajax friendly
    }

    //if all the data is fine, then check whether password is the same as repassword, and do the query
    if ($passValid) {
      		if (($password == $password2)){

                $password = password_hash($password, PASSWORD_BCRYPT);
                //check for special characters for all of the inputs, exclude TRUE, FALSE as well
          			$InsertUser = "INSERT INTO users (username, pwd, forename, surname, email) VALUES ('$username', '$password', '$forename', '$surname', '$email')";
                $InsertBank = "INSERT INTO bank (username, balance, totalprofit, totalloss) VALUES ('$username', 100, 0, 0)";

                $InsertInventoryDefaultCard = "INSERT INTO inventory (username, itemID, equipped) VALUES ('$username', 1, 1)";
                $InsertInventoryDefaultColour = "INSERT INTO inventory (username, itemID, equipped) VALUES ('$username', 2, 1)";
                $InsertInventoryDefaultFont = "INSERT INTO inventory (username, itemID, equipped) VALUES ('$username', 3, 1)";

                $InsertStatsBJ = "INSERT INTO statistics (gameID, username, win, lose, streak) VALUES (1, '$username', 0, 0, 0)";
                $InsertStatsSnap = "INSERT INTO statistics (gameID, username, win, lose, streak) VALUES (2, '$username', 0, 0, 0)";
                $InsertStatsChase = "INSERT INTO statistics (gameID, username, win, lose, streak) VALUES (3, '$username', 0, 0, 0)";

          			if (mysqli_query($connect, $InsertUser) == TRUE){
                  mysqli_query($connect, $InsertBank);
                  //mysqli_query($connect, $InsertInventory);

                  mysqli_query($connect, $InsertInventoryDefaultCard);
                  mysqli_query($connect, $InsertInventoryDefaultColour);
                  mysqli_query($connect, $InsertInventoryDefaultFont);

                  mysqli_query($connect, $InsertStatsBJ);
                  mysqli_query($connect, $InsertStatsSnap);
                  mysqli_query($connect, $InsertStatsChase);
          				header("Location: ../login/login.php");
                  die("");
          			}
          			else {
                  $UsernameCheck = "SELECT username FROM users WHERE username='$username'";
                  $result = mysqli_query($connect, $UsernameCheck);
                  $row = mysqli_fetch_assoc($result);
                  if (count($row) == 0){
                    // Non-Unique email
                    header("Location: register.php?errorID=11");
                    die("");
                  }
                  else {
                    // Non-Unique username
            				header("Location: register.php?errorID=12");
                    die("");
                  }
          			}
      		}
          else{
            // Passwords don't match
            header("Location: register.php?errorID=13");
            die("");
          }
    }
	}
}



?>
