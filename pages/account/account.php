<?php
  include("../page-assets/session_validation.php");
  include("account_interface.php");
?>

<!DOCTYPE html>
<html>
  <head>
    <!-- link to the stylesheet for the pages -->
    <link rel="stylesheet" href="../../style-sheet/mainStyles.css">
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
    <link rel="stylesheet" type="text/css" href="../../style-sheet/navStyle.css">
    <meta charset="utf-8">
    <title>Account Page</title>
  </head>
  <body>
    <div id="nav-placeholder"></div>
    <div class="basicBorder">
        <a href="../home/home.php"><img src="../page-assets/logo.png" alt="logo" class="entryPageLogo"></a>
        <br><br>
        <!-- Table for the user to login and put in their details into the form -->
          <table class="tableForm">
            <tr>
              <td class="tableIndex">Username:</td>
              <td><?php echo $username ?><br></td>
            </tr>
            <tr>
              <td class="tableIndex">Email:</td>
              <td><?php echo $email ?><br></td>
            </tr>
            <tr>
              <td class="tableIndex">Forename:</td>
              <td><?php echo $forename ?><br></td>
            </tr>
            <tr>
              <td class="tableIndex">Surname:</td>
              <td><?php echo $surname ?><br></td>
            </tr>
          </table>
        <br>
    <script>
      $(function(){
        $("#nav-placeholder").load("../page-assets/navbar.php");
      });
    </script>
  </body>
</html>
