<?php
  include("navbar_interface.php");
 ?>

<!DOCTYPE html>
<form action="../page-assets/logout_interface.php"  method="post" id="logoutForm"> </form>

<!-- Navigation Bar
	Anything noted with %VAR% is a value that will be grabbed from the database -->
	<ul class="navbar">
    <li class="navitem"><a class="navitem"><?php echo $username ?></a></li>
    <li class="navitem"><a class="navitem">Coins: <?php echo $balance ?></a></li>

    <li class="navitem" style="float:right"><a class="navitem logoutbut" onclick="logout()">Logout</a></li>
    <li class="navitem" style="float:right">

  	  <!-- Drop Down Menu -->
  		<div class="dropdown">
  			<a class="navitem menubut">Menu</a>
  			<!-- Drop Down Menu Options/Content -->
  			<div class="drop-content">
  				<a class="navitem" href="../home/home.php">HOME</a>
  				<a class="navitem" href="../shop/shop.php">SHOP</a>
  				<a class="navitem" href="../inventory/inventory.php">INVENTORY</a>
  				<a class="navitem" href="../account/account.php">PROFILE</a>
  			</div>
  		</div>
    </li>
  </ul>

  <script>
  function logout() {
    console.log("logout");
    document.getElementById("logoutForm").submit();
  }
  </script>
