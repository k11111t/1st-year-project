<?php

  $errorMessages = array("Missing Forename!",
                         "Missing Surname!",
                         "Missing Username!",
                         "Missing Email!",
                         "Missing Password!",
                         "Illegal Characters in Forename!",
                         "Illegal Characters in Surname!",
                         "Illegal Characters in Username!",
                         "Illegal Characters in Email!",
                         "Illegal Characters in Password!",
                         "Password fails to meet criteia (CRITERIA)",
                         "That Username is already in use!",
                         "That Email is already in use!",
                         "Passwords do not match!",
                         "You must agree to condition [2]!");

  if (isset($_GET["errorID"])) {
    $errorID = $_GET["errorID"];
    switch ($errorID) {
      // FORENAME
      case 0:
      case 5:
      $errorHighlight = 1;
      $errorMessage = $errorMessages[$errorID];
      break;
      // SURNAME
      case 1:
      case 6:
      $errorHighlight = 2;
      $errorMessage = $errorMessages[$errorID];
      break;
      // USERNAME
      case 2:
      case 7:
      case 11:
      $errorHighlight = 3;
      $errorMessage = $errorMessages[$errorID];
      break;
      // EMAIL
      case 3:
      case 8:
      case 12:
      $errorHighlight = 4;
      $errorMessage = $errorMessages[$errorID];
      break;
      // PASSWORD
      case 4:
      case 9:
      case 10:
      case 13:
      $errorHighlight = 5;
      $errorMessage = $errorMessages[$errorID];
      break;
      case 14:
      $errorHighlight = 6;
      $errorMessage = $errorMessages[$errorID];
      break;
      default:
      $errorHighlight = 0;
      unset($errorID);
    }
  }

 ?>


<!DOCTYPE html>
<html>

  <head>
    <!-- link to the stylesheet for the pages -->
    <link rel="stylesheet" href="../../style-sheet/mainStyles.css">
    <meta charset="utf-8">
    <title>Register Page</title>
  </head>

  <body>
    <!-- FORM NEEDED FOR DB INTERACTION -->
    <form action="register_interface.php" method="post">
      <!-- BASIC BORDER CONTAINS ALL CONTENT OF PAGE -->
      <div class="basicBorder">

        <a href="../welcome/welcome.php"><img src="../page-assets/logo.png" alt="logo" class="entryPageLogo"></a>
        <br><br>
        <i>Already have an account? <a href = "../login/login.php">Login here</a></i>

        <br>
        <?php if (isset($errorID)) echo "<div class='basicBorder errorBorder'>$errorMessage</div><br>" ?>
        <br>

        <!-- Table for the user to register and put in their details into the form -->
        <table class="tableForm">
          <tr>
            <td class="tableIndex">First name:</td>
            <td>
            <input type="text" name="forename" class="<?php if($errorHighlight == 1) echo "error"?>"><br></td>
          </tr>
          <tr>
            <td class="tableIndex">Last name:</td>
            <td><input type="text" name="surname" class="<?php if($errorHighlight == 2) echo "error"?>"><br></td>
          </tr>
          <tr>
            <td class="tableIndex">Username:</td>
            <td><input type="text" name="username" class="<?php if($errorHighlight == 3) echo "error"?>"><br></td>
          </tr>
          <tr>
            <td class="tableIndex">Email:</td>
            <td><input type="text" name="email" class="<?php if($errorHighlight == 4) echo "error"?>"><br></td>
          </tr>
          <tr>
            <td class="tableIndex">Password:</td>
            <td><input type="password" name="password" class="<?php if($errorHighlight == 5) echo "error"?>"><br></td>
          </tr>
          <tr>
            <td class="tableIndex">Repeat Password:</td>
            <td><input type="password" name="repassword" class="<?php if($errorHighlight == 5) echo "error"?>"><br></td>
          </tr>
        </table>
        <br>

        <div class="basicBorder errorBorder"</div>
        <!-- CONSENT FOR DRINKING -->
        <p class="text"><b>IMPORTANT:</b></p><br>
        <p class="text">I confirm that I give consent to be subjected to gambling<br>
                        <t>themes through a simulated manner.</p>
        <br><br>
        <p class="importantText">*</p><input type= "checkbox" name = "doesAgree" class="<?php if($errorHighlight == 5) echo "error"?>"> I agree to the above statement.
      </div>
        <br><br>


        <!-- REGISTER BUTTON -->
        <button class="center">Register</button>
      </div>
    </form>
  </body>
</html>
