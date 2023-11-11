<?php
  include("../page-assets/session_validation.php");
?>

<!DOCTYPE html>
<html>
  <head>
    <!-- link to the stylesheet for the pages -->
    <link rel="stylesheet" href="../../style-sheet/mainStyles.css">
    <link rel="stylesheet" href="../../style-sheet/modalStyle.css">
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
    <link rel="stylesheet" type="text/css" href="../../style-sheet/navStyle.css">
    <meta charset="utf-8">
    <title>Shop Page</title>
  </head>
  <body>
    <div id="nav-placeholder"></div>
    <script>
      $(function(){
        $("#nav-placeholder").load("../page-assets/navbar.php");
      });
    </script>

    <div class="basicBorder" style="padding:0">
      <br>
      <p class="titleText">Item Shop</p>
      <br>
      <?php
        $errorMessages = array("You already own that item!",
                               "Failed to get balance, try again later.",
                               "Failed to get item price, try again later.",
                               "Failed to update your inventory, try again later.",
                               "Failed to update your balance, try again later.",
                               "You can't afford that item!");
        if (isset($_GET["errorID"])) {
          $errorID = $_GET["errorID"];
          if($errorID <= 5 and $errorID >= 0) {
            $errorMessage = $errorMessages[$errorID];
          }
        }


        if (isset($errorMessage)) echo "<div class='basicBorder errorBorder'>$errorMessage</div><br>";

        //select info from database
        $getShop = "SELECT * FROM store";
        $result = mysqli_query($connect, $getShop);
        if(!$result){
          header("Location: register.php?errorID=14");
          die("");
          die('could not select stuff from database');
        }
        //get the users inventory
        $queryInventory = "SELECT itemID FROM inventory WHERE username='$username'";
        $resultInventory = mysqli_query($connect, $queryInventory);
        if(!$resultInventory){
          die('could not query database');
        }
        $ownedItems = [];
        while ($row = mysqli_fetch_assoc($resultInventory)) {
          array_push($ownedItems, $row['itemID']);
        }

        //print the table of images
        //$i is item type
        for($i=0;$i<=2;$i++){
          //select items with the same item type from the store
          $getItem = "SELECT * FROM store WHERE (itemType=$i AND visible=1)";
          $result = mysqli_query($connect, $getItem);
          if(!$result){
            die('could not query database');
          }
          //print table for each item type
          echo'<table class="tableBorder">';
          //print title based on item type
          switch($i){
            case(0): echo"<th colspan='99' class='subtitleText'>Card Back</th><th></th>"; break;
            case(1): echo"<th colspan='99' class='subtitleText'>Text Colour</th><th></th>"; break;
            case(2): echo"<th colspan='99' class='subtitleText'>Font Style</th><th></th>";break;
          }
          echo'<tr>';

          //print all the items
          while($row = mysqli_fetch_assoc($result)){
            $itemID = $row['itemID'];
            $itemIcon = $row['icon'];
            $itemName = $row['name'];
            $itemDescription = $row['description'];
            $itemCost = $row['cost'];
            //image
            // CHECK IF OWNED
            if (!in_array($itemID, $ownedItems)) {
              echo'<td style="padding:20;"><img class="myImg" id="img'.$itemID.'" src="'.$itemIcon.'" alt="blackjack" onclick="popUp(img'.$itemID.')" style="width:100%;max-width:300px"></td>';
              //modal
              echo
              '<div id="myModal'.$itemID.'" class="modal">
                <span class="close" id="close'.$itemID.'">&times;</span>
                <img class="modal-content" id="modImg'.$itemID.'">
                <div id="description'.$itemID.'" name="description'.$itemID.'" class="descriptionText">
                  <b>'.$itemName.'</b><br><br>'.$itemDescription.'<br><br>
                </div>
                <form action="shop_interface.php" method="post">
                  <button name="button'.$itemID.'">Buy for: '.$itemCost.'</button>
                </form>
              </div>';
            }
            else {
              echo'<td style="padding:20;"><img class="myImg" id="img'.$itemID.'" src="'.$itemIcon.'" alt="blackjack" style="width:100%;max-width:300px;opacity:0.2;"></td>';
            }
            // END OF CHECK
          }
          echo'</tr></table>';
        }
      ?>
    </div>

    <script>
      function popUp(i) {
        // Get the image and insert it inside the modal - use its "alt" text as a description
        var img = document.getElementById(i.id);
        var no = (i.id).substring(3);
        descript = "description" + no;
        var mod = "myModal" + no;
        var modImage = "modImg" + no;
        console.log(no);
        var modalImg = document.getElementById(modImage);
        var descriptionText = i.alt;
        ///Get the modal
        var modal = document.getElementById(mod);
        console.log(modal);
        // img.onclick = function(){
        modal.style.display = "block";
        modalImg.src = img.src;
        // caption.innerHTML = captionText;
        //document.getElementById(descript).innerHTML = descriptionText;
        console.log(close);
        // Get the <span> element that closes the modal
        var span = document.getElementById("close"+no);
        span.onclick = function() {
          modal.style.display = "none";
        }
      }
    </script>
  </body>
</html>
