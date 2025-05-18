<!DOCTYPE html>
<html>

  <head>
    <!-- link to the stylesheet for the pages -->
    <link rel="stylesheet" href="../../style-sheet/mainStyles.css">
    <meta charset="utf-8">
    <title>Login Page</title>
  </head>

  <body>
    <form action="login_interface.php" method="post">
      <div class="basicBorder">
        <a href="../welcome/welcome.php"><img src="../page-assets/logo.png" alt="logo" class="entryPageLogo"></a>
        <br><br>
        <!-- Table for the user to login and put in their details into the form -->
        <table class="tableForm">
          <tr>
            <td class="tableIndex">Username:</td>
            <td><input type="text" name="username" class="<?php if(isset($_GET["errorID"]) && $_GET["errorID"] == 1) echo "error"?>"><br></td>
          </tr>
          <tr>
            <td class="tableIndex">Password:</td>
            <td><input type="password" name="password" class="<?php if(isset($_GET["errorID"]) && $_GET["errorID"] == 1) echo "error"?>"><br></td>
          </tr>
        </table>
        <br><br>
        <i>Don't already have an account? <a href = "../register/register.php">Register here</a></i>
        <br><br>
        <button class="center">Login</button>
      </div>
    </form>
  </body>

</html>
