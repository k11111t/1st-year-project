<?php
  $getNumberOfCoins = "SELECT bank.balance
                       FROM bank
                       WHERE bank.username = '$username'";
  $result = mysqli_query($connect, $getNumberOfCoins);
  $row = mysqli_fetch_assoc($result);
  if ($row["balance"] < 50) {
    header("Location: home.php?errorID=1");
    die("");
  }
?>
