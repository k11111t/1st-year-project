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

   /*
   ERROR MESSAGE ID TABLE:
   0 - Failed to get item type
   1 - Failed to unequip item
   2 - Failed to equip item
   */

  //determine which button was pressed
  $getNumberOfStoreItems = "SELECT itemID FROM store";
  $result = mysqli_query($connect, $getNumberOfStoreItems);
  for($buttonID=1; $buttonID<=$result->num_rows; $buttonID++){
    if(isset($_POST["button$buttonID"])){
      break;
    }
  }

  session_start();
  $username = $_SESSION['username'];

  //get item type
  $getItemType = "SELECT itemType FROM store WHERE itemID=$buttonID";
  $result = mysqli_query($connect, $getItemType);
  if(!$result){
    //failed to get item type
    header("Location:inventory.php?errorID=0");
    die("");
  }
  $row = mysqli_fetch_assoc($result);
  $itemType = $row['itemType'];

  //unequip current item
  $unequipItem = "UPDATE inventory INNER JOIN store ON store.itemType=$itemType AND store.itemID=inventory.itemID SET equipped=0 WHERE (equipped=1 AND username='$username')";
  $result = mysqli_query($connect, $unequipItem);
  if(!$result){
    //failed to unequip item
    header("Location:inventory.php?errorID=1");
    die("");
  }

  //equip item
  $equipItem = "UPDATE inventory SET equipped=1 WHERE (itemID=$buttonID AND username='$username')";
  $result = mysqli_query($connect, $equipItem);
  if(!$result){
    //failed to equip item
    header("Location:inventory.php?errorID=2");
    die("");
  }

  echo'success';
  header("Location:inventory.php");
  die("");
?>
