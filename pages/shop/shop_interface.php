<?php
if($_POST){
  session_start();
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
  0 - Player already owns item
  1 - Failed to get balance
  2 - Failed to get item cost
  3 - Could not update users inventory
  4 - Couldn't update balance
  5 - Couldn't afford item
  */

  //get number of items in shop
  $getNumberOfStoreItems = "SELECT itemID FROM store";
  $result = mysqli_query($connect, $getNumberOfStoreItems);

  //check which button has the user pressed
  for($buttonID=1; $buttonID<=$result->num_rows; $buttonID++){
    if(isset($_POST["button$buttonID"])){
      break;
    }
  }
  $username = $_SESSION['username'];

  //check if the users already owns this item
  $getItemOwned = "SELECT itemID FROM inventory WHERE (username = '$username' AND itemID=$buttonID)";
  $result = mysqli_query($connect, $getItemOwned);
  if($result){
    $row = mysqli_fetch_assoc($result);
    if(isset($row)){
      //player already owns the item
      header("Location:shop.php?errorID=0");
      die("");
    }
  }

  //fetch users currency - to compare with the price of the item
  $getCurrency= "SELECT balance FROM bank WHERE username='$username'";
  $result = mysqli_query($connect, $getCurrency);
  if(!$result){
    //could not get users balance
    header("Location:shop.php?errorID=1");
    die("");
  }
  $row = mysqli_fetch_assoc($result);
  $userBalance = $row['balance'];

  //get the cost of a selected item
  $getCost = "SELECT cost FROM store WHERE itemID=$buttonID";
  $result = mysqli_query($connect, $getCost);
  if(!$result){
    //could not get item cost
    header("Location:shop.php?errorID=2");
    die("");
  }
  $row = mysqli_fetch_assoc($result);
  $itemCost = $row['cost'];

  //check if the user can afford it
  if($itemCost<=$userBalance){
    //update users inventory
    $updateInventory = "INSERT INTO inventory (itemID, username, equipped) VALUES ($buttonID, '$username', 0)";
    $result = mysqli_query($connect, $updateInventory);
    if(!$result){
      //couldn't get inventory
      header("Location:shop.php?errorID=3");
      die("");
    }

    //update users balance
    $userBalance = $userBalance - $itemCost;
    $setCurrency = "UPDATE bank SET balance=$userBalance WHERE username='$username'";
    $result = mysqli_query($connect, $setCurrency);
    if(!$result){
      //couldn't update users currency
      header("Location:shop.php?errorID=4");
      die("");
    }

    echo'successfully bought an item';
    header("Location:shop.php");
    die("");
  }
  else{
    //NEEDS FIXING
    $getVisibleItems = "SELECT itemID FROM store WHERE visible=1";
    $result = mysqli_query($connect, $getVisibleItems);
    $temp = $buttonID + $result->num_rows;
    //cannot afford the item
    header("Location:shop.php?errorID=5");
    die("");
  }
}

?>
