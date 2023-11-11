<?php
  include("../page-assets/session_validation.php");
  include("home_interface.php");
 ?>

<!DOCTYPE html>
<html>
  <head>
    <!-- link to the stylesheet for the pages -->
    <link rel="stylesheet" href="../../style-sheet/mainStyles.css">
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
    <link rel="stylesheet" type="text/css" href="../../style-sheet/navStyle.css">
    <link rel="stylesheet" href="../../style-sheet/homeStyle.css">
    <meta charset="utf-8">

    <title>Home Page</title>
  </head>
  <body>

    <div id="nav-placeholder"></div>
    <script>
      $(function(){
        $("#nav-placeholder").load("../page-assets/navbar.php");
      });
    </script>

    <form action="host_interface.php" method="post" id="createRoomForm">
		  <input id="hostname" name="hostname" hidden></input>
		</form>

    <form action="join_interface.php" method="post" id="joinRoomForm">
      <input id="roomUsername" name="roomUsername" hidden></input>
    </form>

    <div class="basicBorder">

      <?php if (isset($_GET["errorID"]) and $_GET["errorID"] == 1) echo "<div class='basicBorder errorBorder'>You don't have sufficient funds to play!<br>
                                                                                                              You can earn more from your login bonus.</div><br>"; ?>

      <h1>Choose a Game</h1>
      <div style="height:378px; display:inherit;"> <!--Used to set the height of the images so that the rest of the elements can format well -->
        <div class="flip-box">
          <div class="flip-box-inner">
            <div class="flip-box-front">
              <img src="../page-assets/cards/backs/blueCard.png" alt="blackjack" class = "cardFront">  <!-- display the front face as an image of 2 cards -->
            </div>
            <div class="flip-box-back blue">  <!-- class used for showing the users stats for that game and letting them create a room in that ga,m -->
              <h2><?php echo $g1Name?></h2>
              <p class="text"><?php echo $g1Desc?><br>
                <br>
                Max Players: <?php echo $g1MaxP?> <br>
                Min Players: <?php echo $g1MinP?> <br>
                <br>
                Games played: <?php echo $g1GamesPlayed?> <br>
                Games won: <?php echo $g1GamesWon?> <br>
                Games lost: <?php echo $g1GamesLost?> <br>
                Current win streak: <?php echo $g1WinStreak?></p>
              <br><br>
              <button onclick="createRoom()">Create Room</button>
            </div>
          </div>
        </div>
        <div class="flip-box">
          <div class="flip-box-inner">
            <div class="flip-box-front">
              <img src="../page-assets/cards/backs/blackCard.png" alt="blackjack" class = "cardFront">
            </div>
            <div class="flip-box-back black">
              <h2><?php echo $g2Name?></h2>
              <p class="text"><?php echo $g2Desc?><br>
                <br>
                Max Players: <?php echo $g2MaxP?> <br>
                Min Players: <?php echo $g2MinP?> <br>
                <br>
                Games played: <?php echo $g2GamesPlayed?> <br>
                Games won: <?php echo $g2GamesWon?> <br>
                Games lost: <?php echo $g2GamesLost?> <br>
                Current win streak: <?php echo $g2WinStreak?></p>
              <br><br>
              <button command="none">Coming Soon</button>
            </div>
          </div>
        </div>
        <div class="flip-box">
          <div class="flip-box-inner">
            <div class="flip-box-front">
              <img src="../page-assets/cards/backs/redCard.png" alt="blackjack" class = "cardFront">
            </div>
            <div class="flip-box-back red">
              <h2><?php echo $g3Name?></h2>
              <p class="text"><?php echo $g3Desc?><br>
                <br>
                Max Players: <?php echo $g3MaxP?> <br>
                Min Players: <?php echo $g3MinP?> <br>
                <br>
                Games played: <?php echo $g3GamesPlayed?> <br>
                Games won: <?php echo $g3GamesWon?> <br>
                Games lost: <?php echo $g3GamesLost?> <br>
                Current win streak: <?php echo $g3WinStreak?></p>
              <br><br>
              <button command="none">Coming Soon</button>
            </div>
        </div>
      </div>
    </div>
      <br>
      <h1>Current Rooms</h1>
      <table>
        <tr>
          <th>Room Host</th>
          <th>Gamemode</th>
          <th>Room Members</th>
          <th>Max Players</th>
          <th>Min Players</th>
          <th>Join</th>
        </tr>
        <?php
          $getGameRooms = "SELECT * FROM gameRoom";
          $gameRoomResult = mysqli_query($connect, $getGameRooms);
          while($row = mysqli_fetch_assoc($gameRoomResult)) {
            $roomUsername = $row['username'];
            $gamemode = $row['gameID'];
            echo '<tr>';
            echo '<td>'.$roomUsername.'</td>';
            echo '<td>'.$gamemode.'</td>';
            echo '<td>'.$row['currentNoPlayers'].'</td>';
            $getMaxMinPlayers = "SELECT gamemode.maxplayers, gamemode.minplayers
                                 FROM gamemode
                                 WHERE gamemode.gameID = '$gamemode'";
            $result = mysqli_query($connect, $getMaxMinPlayers);
            if(!$result){
              die('error in fetching room capacity');
            }
            $row = mysqli_fetch_assoc($result);
            echo '<td>'.$row['maxplayers'].'</td>';
            echo '<td>'.$row['minplayers'].'</td>';
            echo '<td><button onclick="joinRoom(\''.$roomUsername.'\')">Join Game</button></td>';
            echo '</tr>';
          }
          ?>
    </div>
    <script type="text/javascript">
      const ws = new WebSocket("ws://10.2.232.171:3000");
      //const ws = new WebSocket("ws://localhost:3000");

      function func() {}

      ws.onopen = () => func();
      ws.onclose = () => func();

      function createRoom() {
        //console.log("this works1");
          msg = {
            type: "createRoom",
            responseLevel: 1,
            roomUsername: "<?php echo $username?>",
            gameType: "BlackJack",
             <?php $getMinMaxPlayers = "SELECT maxplayers, minplayers FROM gamemode WHERE gameID=1";
      		      $result = mysqli_query($connect, $getMinMaxPlayers);
      		      $row = mysqli_fetch_assoc($result);
      		      echo'maxNoPlayers: '.$row['maxplayers'].',';
      		      echo'minNoPlayers: '.$row['minplayers'];?>
          }

          ws.send(JSON.stringify(msg));
        }

      function joinRoom (userNameToJoin) {
          msg = {
            type: "joinRoom",
            responseLevel: 1,
            roomUsername: userNameToJoin,
            username: "<?php echo $username?>",
          }

          ws.send(JSON.stringify(msg));
      }

      ws.onmessage = function (response) {
         var msg = JSON.parse(response.data);
         switch (msg.type) {
            case "createRoomResponse":
            	console.log(4);
            	var hostUsername = msg.roomUsername;
            	console.log(hostUsername);
            	document.getElementById("hostname").value = hostUsername;
            	//console.log(document.getElementById("hostname").value);
            	document.getElementById("createRoomForm").submit();
            	break;
            case "joinRoomResponse":
        	     console.log("recieved");
               var hostUsername = msg.roomUsername;
               document.getElementById("roomUsername").value = hostUsername;
               document.getElementById("joinRoomForm").submit();
         }
      }
    </script>
  </body>
</html>
