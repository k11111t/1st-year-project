<?php
  include("../page-assets/session_validation.php");
  include("entry_validation.php");

	$_SESSION['hostname']=$_POST['roomUsername'];
	header("Location:../game/game.php");
	die("");
?>
