<?php
  include("equipped_interface.php");
  $roomUsername = $_SESSION['hostname'];
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="../../style-sheet/mainStyles.css">
  <link rel="stylesheet" href="../../style-sheet/gameStyle.css">
  <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
  <link href="https://fonts.googleapis.com/css?family=Delius&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Oswald:700&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="../../style-sheet/navStyle.css">
  <meta charset="utf-8">
</head>
<body onunload="disconnectFromGame()" onload="onLoad()" onresize="centreCards()" scroll="no" style="overflow: hidden" id="body">
    <!-- NAVBAR -->
  <div id="nav-placeholder" style="z-index: 0;"></div>
  <script>
    $(function(){
      $("#nav-placeholder").load("../page-assets/navbar.php");
    });
  </script>

      <!-- DEALER -->

      <div class="fixTop" id="dealerDisplay"> <!-- div containing the cards that the dealer has -->
        <br><div id="dealerPoints"></div>
      </div>


      <!-- PLAYER -->


      <div class = "fixBottom" id="userCardDisplay"> <!-- div containing the cards that the user has -->
      <br> <div id="userPoints" style="bottom: 0px; position:absolute; width:100%;"></div>
      </div>



    <div class = "fixLeft" id="userList" style="z-index: -1;">
      <p id="playerName">Name of player</p>
      <div id="cardList">
        <!-- display all the cards that the user has -->
        <!-- if it is not a user, display the backs of the cards -->
      </div>
    </div>


  <!--  <button onclick="processUserCards({cards:['2C', '3H', '3H', '3H', '3H', '4D'], points:9})" style="top:300px; position:absolute; left:400px;">Click me to add user cards</button>
    <button onclick="processDealerCards({cards:['2C'], points:20})" style="top:300px; position:absolute; ">Click me to add dealer cards</button>
    <input type="text" style="top:400px; position:absolute; " id="nameNewUser"></input>
    <button onclick="addUserToList()" style="top:400px;left:400px; position:absolute;">Click to add user</button>
    <button onclick="createLeaderboardTable([{username: 'kit', score: 10}, {username: 'ollie', score: 1}, {username: 'justin', score: 9}])">click to show leadeboard</button>
    <button onclick="clearMessageSpot()">click to clear leaderboard</button>

-->
    <div id="messageSpot" class="basicBorder" style="display: none"></div>

    <div class = "fixRightTop" id="buttonList">
      <p id="status">OFFLINE</p>
      <button class="hitButton" id="hit" onclick="hit()">Hit</button>
      <br><br>
      <button class="standButton" id="stand" onclick="stand()">Stand</button>
      <br><br>
      <!-- needs fixing for sryles -->
      <button id="startGame" onclick="startGame()" style="display: none">Start Game</button>
      <button id="ready" onclick="ready()" style="display: none">New Round</button>
    </div>

    <div class = "fixRightBottom" id="Chat">
		<div class="chatMessages" id="messagePane"></div>
        <div class="chatBottom">
			<input type="text" name="text" id="userText" value="" placeholder="type your chat message" />
			<button onclick = "sendMessage()">Send</button>
        </div>
    </div>

</body>
<script>

  var roomUsername = <?php  echo"\"$roomUsername\""; ?>;
  var username = <?php echo "\"$username\""; ?>;
  
  if (username == roomUsername) {
	document.getElementById("startGame").style.display = "inline";
  }

  var deckOfAllCards = [];
  var dealerCards =[];
  var userCards = [];

  var noOfUsers = [];
  var difUsersCards = []
  var divLeft = document.getElementById("userList");
  var playerNameCounter = 0;

  // set users equipped items
  var cardBack = <?php echo "\"$cardBack\"" ?>;
  switch(cardBack)
  {
    case "1": cardBack = "../page-assets/cards/backs/redCard.png";
    break;
    case "4": cardBack = "../page-assets/cards/backs/blueCard.png";
    break;
    case "7": cardBack = "../page-assets/cards/backs/pinkCard.png";
    break;
    case "10": cardBack = "../page-assets/cards/backs/blackCard.png";
    break;
  }

  var fontColour = <?php echo "\"$nameColour\"" ?>;
  switch(fontColour)
  {
    case "2": fontColour = "White";
    break;
    case "5": fontColour = "Gold";
    break;
    case "8": fontColour = "#b7e3e2"; // diamond
    break;
    case "11": fontColour = "#BFFF00"; // lime green
    break;
  }

  var fontType = <?php echo "\"$nameFont\"" ?>;
  switch(fontType)
  {
    case "3": fontType = "Arial";
    break;
    case "6": fontType = "Oswald"; // equivalent of impact
    break;
    case "9": fontType = "Courier New";
    break;
    case "12": fontType = "Delius"; // equivalent of comic sans
    break;
    default: fontType = "Arial";
    break;
  }


  function onLoad(){
    generateDeck();
    resizeDivs();
  }

  function resizeDivs()
  {
    var height = window.innerHeight;
    document.getElementById("body").style.fontSize = height / 40 + "px";
    // display for the left side panel which shows the users and their cards
    if(noOfUsers.length != 0)
    {
      var divLeft = document.getElementById("userList");
      var width = window.innerWidth;
      // height of one block per user is the font size + height of the miniature cards + border top and bottom(4) + padding top and bottom(20) + margin bottom(10)
      var offsetHeight = divLeft.offsetHeight;
      var shift = (height - offsetHeight)/2 + "px"; // variable to keep the scale of the left panel to the height of the screen constant
      divLeft.style.top = shift;
      divLeft.style.display = 'inline';
    }
    else // do not show left panel if there are no users in the room
    {
      var divLeft = document.getElementById("userList");
      divLeft.style.display = 'none';
    }

    // display for the buttons
    var height = window.innerHeight;
    var width = window.innerWidth;
    // set width and height of hit and stand buttons
    var hitButton = document.getElementById("hit");
    hitButton.style.height = height / 15 + "px";
    hitButton.style.width = width / 7 + "px";
    var standButton = document.getElementById("stand");
    standButton.style.height = height / 15 + "px";
    standButton.style.width = width / 7 + "px";

    var divRightTop = document.getElementById("buttonList");
    // keep the scale of the buttons the same
    divRightTop.style.top = height/10 + "px";
    divRightTop.style.right = width/20 + "px";
    var divHeight = height / 4 + "px"; // buttons should be in upper right quadrant of the page
    divRightTop.style.height = divHeight;

    // display for the chat
    var divRightBottom = document.getElementById("Chat"); // should take up half the height of the right side but a quarter of the width
    var divHeightChat = height / 2 + "px";
    divRightBottom.style.height = divHeightChat;
    var divWidthChat = width / 4 + "px";
    divRightBottom.style.maxWidth = divWidthChat;
    userText = document.getElementById("userText");
    userText.style.width = width/4 -57-100 +  "px"; // width of chat display minus width of the button minus padding
    var messageSpot = document.getElementById("messageSpot");
    messageSpot.style.minHeight = height * 1/8 + "px";
	messageSpot.style.paddingTop = height * 1/16 + "px";
	messageSpot.style.paddingBottom = height * 1/16 + "px";
	messageSpot.style.marginTop = height * (2/7) + "px"; // height of card is 2/7 of height. taking into account the height of the message space +  padding top
  }


  function centreCards()
  {
    resizeCards();
    var height = window.innerHeight;
    height = height / 3.5
    if(userCards.length != 0)
    {
      var width = window.innerWidth;
      if(userCards.length < 6)
        width = width - userCards.length * height * (5/7);
      else {
        width = width - 5 * height * (5/7);
        document.getElementById("userCardDisplay").style.overflowX = "scroll";
      }
      width = width / 2;
      document.getElementById("userCardDisplay").style.maxWidth = (5 * height * (5/7)) + 10 + "px"; // maximum of 5 cards being visible at one time

      document.getElementById("userCardDisplay").style.bottom = (-1) * (height/3) + "px"; // display top 2/3 of the card
      var width2 = width;
      width = width + "px";
      document.getElementById("userCardDisplay").style.left = width; // realign the user cards when the size of the window changes
      document.getElementById("userPoints").style.bottom =  (-height* 5/7 *2/3 + 300) + "px";//height* 5/7 *2/3 + 2/5*height+ "px";

      width2 = width2 - 60;
      width2 = width2 + "px";
      document.getElementById("Chat").style.width = width2; //align the size of the chat box when the user cards div gets large
    }
    else {

    }

    if(dealerCards.length != 0)
    {
      var widthT = window.innerWidth;
      if(dealerCards.length < 6)
        widthT = widthT - dealerCards.length * height * (5/7);
      else {
        widthT = widthT - 5 * height * (5/7);
        document.getElementById("dealerDisplay").style.overflowX = "scroll";
      }
      document.getElementById("dealerDisplay").style.maxWidth = (5 * height * (5/7)) + "px"; // maximum of 5 cards being visible at one time
      document.getElementById("dealerDisplay").style.top = (-1) * (height/3) +55 + "px"; // display top 2/3 of the card
      widthT = widthT / 2;
      widthT = widthT + "px";
      document.getElementById("dealerDisplay").style.left = widthT;
      document.getElementById("dealerDisplay").style.height = height + 30 + 'px';
      document.getElementById("dealerPoints").style.top = height* 5/7 *2/3 + "px";

    }
    resizeDivs();

  }


  function resizeCards() // resize the cards whenever the size of the window changes
  {
    var height = window.innerHeight;
    height = height / 3.5
    for( var i = 0; i < userCards.length; i++)
    {
      userCards[i].height = height;
      userCards[i].width = 5/7 * height;
    }

    for( var i = 0; i < dealerCards.length; i++)
    {
      dealerCards[i].height = height;
      dealerCards[i].width = 5/7 * height;
    }
  }

  function playerStandBorder(user) // user has to be a string else it won't work
  {
  	var playerNo = document.getElementById(user);
  	playerNo.style.border = "5px solid blue";
  }

  function playerBustBorder(user) // user has to be a string else it won't work
  {
  	var playerNo = document.getElementById(user);
  	playerNo.style.border = "5px solid red";
  }

  function playersTurnBorder(user) // user has to be a string else it won't work
  {
  	var playerNo = document.getElementById(user);
  	playerNo.style.border = "3px solid white";
  }

  function playerWaitBorder(user) // user has to be a string else it won't work
  {
  	var playerNo = document.getElementById(user);
  	playerNo.style.border = "1px solid yellow";
  }
	
	function addMessage(text)
    {
		var message = document.createElement("p");
		message.innerText = text;
		message.style.marginBottom = "5px";
		document.getElementById("messagePane").appendChild(message).scrollIntoView(); // makes the last element appended to the bottom and the scroller moves to the bottom of the div
    }
    
    function sendMessage()
    {
		var text = document.getElementById("userText").value;
		document.getElementById("userText").value = "";
		
		var message = {
			type: "chatSend",
			text: text,
			username: username,
			roomUsername: roomUsername,
			responseLevel: 2
		}
		ws.send(JSON.stringify(message));
    }




</script>
<script type="text/javascript" src="game_code.js"></script>
</html>
