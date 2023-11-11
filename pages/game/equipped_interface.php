<?php
  include("../page-assets/session_validation.php");
  $cardBack = 1;
  $nameColour = 2;
  $nameFont = 3;
  //get item type
  $getEquipped = "SELECT inventory.itemID, store.itemType
                  FROM inventory, store
                  WHERE inventory.username = '$username'
                  AND inventory.equipped = 1
                  AND inventory.itemID = store.itemID";
  $result = mysqli_query($connect, $getEquipped);
  if(!$result){
    //failed to get item type
    die("Failed to get equipped items");
  }
  while ($row = mysqli_fetch_assoc($result)) {
    switch($row['itemType']) {
      case 0:
        //card
        $cardBack = $row['itemID'];
        break;
      case 1:
        //colour
        $nameColour = $row['itemID'];
        break;
      case 2:
        //font
        $nameFont = $row['itemID'];
        break;
    }
  }
?>
