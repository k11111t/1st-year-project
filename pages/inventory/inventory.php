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
    <title>Inventory Page</title>
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
      <p class="titleText">Inventory</p>
      <br>
      <?php
        $errorMessages = array("Failed to get item type, try again later.",
                               "Failed to unequip your current item, try again later.",
                               "Failed to equip your current item, try again later.");
        if (isset($_GET["errorID"])) {
          $errorID = $_GET["errorID"];
          if($errorID <= 2 and $errorID >= 0) {
            $errorMessage = $errorMessages[$errorID];
          }
        }

        if (isset($errorMessage)) echo "<div class='basicBorder errorBorder'>$errorMessage</div><br>";

        //select info from database, $i is item type
        $username = $_SESSION['username'];
        for($i=0;$i<=2;$i++){
          //get the correct row from inventory
          $queryInventory = "SELECT inventory.* FROM inventory INNER JOIN store ON store.itemType=$i AND store.itemID=inventory.itemID WHERE username='$username'";
          $resultInventory = mysqli_query($connect, $queryInventory);
          if(!$resultInventory){
            die('could not query database');
          }
          echo'<table class="tableBorder">';
          //determine which item type the item is
          switch($i){
            case(0): echo"<th colspan='99' class='subtitleText'>Card Back</th><th></th>"; break;
            case(1): echo"<th colspan='99' class='subtitleText'>Text Colour</th><th></th>"; break;
            case(2): echo"<th colspan='99' class='subtitleText'>Font Style</th><th></th>";break;
          }
          echo'<tr>';
          //get info from store, based on item type
          $queryStore = "SELECT store.* FROM store WHERE store.itemType=$i";
          $resultStore = mysqli_query($connect, $queryStore);

          //get inventory items that are the same type
          $rowInventory = mysqli_fetch_assoc($resultInventory);

          //iterate through the store items that are the same type
          while($rowStore = mysqli_fetch_assoc($resultStore)){
            //print the image if the item is owned
            if(isset($rowInventory) and $rowStore['itemID']==$rowInventory['itemID']){
              echo'<td>';

              //printing th image and modal
              $itemID = $rowStore['itemID'];
              $itemIcon = $rowStore['icon'];
              $itemName = $rowStore['name'];
              $itemDescription = $rowStore['description'];
              //image

              //determite if the item is equipped
              if($rowInventory['equipped']==1){
                echo'<img class="myImg equipped" id="img'.$itemID.'" src="'.$itemIcon.'" alt="blackjack" onclick="popUp(img'.$itemID.')" style="width:100%;max-width:300px"></td>';
              }
              else{
                echo'<img class="myImg" id="img'.$itemID.'" src="'.$itemIcon.'" alt="blackjack" onclick="popUp(img'.$itemID.')" style="width:100%;max-width:300px"></td>';
              }

              //modal
              echo'<div id="myModal'.$itemID.'" class="modal">
                     <span class="close" id="close'.$itemID.'">&times;</span>
                     <img class="modal-content" id="modImg'.$itemID.'">
                     <div id="description'.$itemID.'" name="description'.$itemID.'" class="descriptionText">
                      <b>'.$itemName.'</b><br><br>'.$itemDescription.'<br><br>
                     </div>
                     <form action="inventory_interface.php" method="post">
                      <button name="button'.$itemID.'">Equip</button>
                     </form>
                   </div>';
              $rowInventory = mysqli_fetch_assoc($resultInventory);
            }

            //print faded image if the item is not owned
            else{
              //change style for this
              $itemID = $rowStore['itemID'];
              $itemIcon = $rowStore['icon'];
              echo'<td><img class="myImg" id="img'.$itemID.'" src="'.$itemIcon.'" alt="blackjack" style="width:100%;max-width:300px; opacity:0.2;"></td>';
            }
          }
          echo'</tr></table>';
        }
      ?>
    </div>
    <script>
      function popUp(i)
      {
        // Get the image and insert it inside the modal - use its "alt" text as a description
        var img = document.getElementById(i.id);
        var no = (i.id).substring(3);
        descript = "description" + no;
        var mod = "myModal" + no;
        var modImage = "modImg" + no;
        var modalImg = document.getElementById(modImage);
        var descriptionText = i.alt;
        ///Get the modal
        var modal = document.getElementById(mod);
        modal.style.display = "block";
        modalImg.src = img.src;
        // Get the <span> element that closes the modal
        var span = document.getElementById("close"+no);
        span.onclick = function() {
          modal.style.display = "none";
        }
      }
    </script>
  </body>
</html>
