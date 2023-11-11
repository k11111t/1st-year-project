
const status = document.getElementById("status");
const messages = document.getElementById("messages");
const form = document.getElementById("form");
const input = document.getElementById("input");

const ws = new WebSocket("ws://10.2.232.171:3000");
//const ws = new WebSocket("ws://localhost:3000");


//global vars save info about user

////////////////////////////////////DECK////////////////////////////////////////////////////////////

function generateDeck(){
	temp = '';
	arrayOfAllCards = ["2S", "3S", "4S", "5S", "6S", "7S", "8S", "9S", "10S", "jS", "qS", "kS", "aS",
					 "2D", "3D", "4D", "5D", "6D", "7D", "8D", "9D", "10D", "jD", "qD", "kD", "aD",
					 "2C", "3C", "4C", "5C", "6C", "7C", "8C", "9C", "10C", "jC", "qC", "kC", "aC",
					 "2H", "3H", "4H", "5H", "6H", "7H", "8H", "9H", "10H", "jH", "qH", "kH", "aH"];
	for(var i=0; i<arrayOfAllCards.length; i++){
		temp = '../page-assets/cards/fronts/'+arrayOfAllCards[i]+'.png';
		deckOfAllCards.push(temp);
	}
}

///////////////////////////////////////USER///////////////////////////////////////////////////////


function updateUserCards(cardsSentByWS)
{
	for(var i = 0; i<cardsSentByWS.length; i++){
		var img = new Image();
		var h = window.innerHeight;
		h = h / 3.5;
		img.src = '../page-assets/cards/fronts/'+cardsSentByWS[i]+'.png';
		img.width = (5/7) * h;
		img.height = h;
		img.style.transform='rotateX(180deg)';
		userCards.push(img);
	}
}


function printUserPoints(value) {
	document.getElementById("userPoints").innerHTML = value;
}


function displayUserCards(){
	var h = window.innerHeight;
	document.getElementById("userCardDisplay").style.bottom = (-1) * (h/3) + "px"; // display bottom 2/3 of the card
	for(var i =0; i<userCards.length; i++){
		document.getElementById("userCardDisplay").appendChild(userCards[i]);
	}

	var div = document.createElement("div");
	div.setAttribute("id", "userPoints");
	div.style.transform = "rotateX(180deg)";
	document.getElementById("userCardDisplay").appendChild(div);
}


function resetUserCards()
{
	var userDisp = document.getElementById("userCardDisplay");
	while (userDisp.hasChildNodes()) {
	  userDisp.removeChild(userDisp.firstChild);
	}

	for(i = userCards.length - 1; i >= 0; i--){
		userCards.pop();
	}
}

function processUserCards(msg){
	resetUserCards();
	updateUserCards(msg.cards);
	displayUserCards();
	printUserPoints("Your Points: " + msg.points);
	centreCards();
}

function getMyInfo(message){
	console.log(message);
		for(var i = 0; i<message.length; i++){
			if(message[i].username == username){
				return message[i];
			}
		}
		console.log('user not found');
}

//////////////////USER LIST////////////////////////////////////////

function resetBorders(){
	userList = document.getElementById("userList").children;
	for(var i = 0; i<userList.length; i++){
		playerWaitBorder(userList[i].id);
	}
}

function processUserList(userListIn, showCards) {
	emptyUserList();
	for (var i = 0; i < userListIn.length; i++) {
		addUserToList(userListIn[i], showCards);
	}
}

function addUserToList(userToAdd, showCards)
{
	var height = window.innerHeight;
	var divLeft = document.getElementById("userList");
	var width = window.innerWidth;
	// height of one block per user is the name plus the height of the image(150). approx 200px in total
	// FONT SIZE IS HEIGHT OF WINDOW DIVIDED 35
	// CARD SIZE IS HEIGHT DIVIDED BY 21 MULTIPLIED BY 2

	var player = document.createElement("div");
	var userName = userToAdd.username;
	noOfUsers.push(userName);
	player.setAttribute("id", userName);
	// below code sets the name and styling in the users panel
	var person = document.createElement("p");
	person.innerText = userName;
	person.style.fontFamily = fontType;
	person.style.color = fontColour;
	person.style.marginTop = "2px";
	player.appendChild(person);
	// below code will be looped and adding the correct face each time
	var playerCards = document.createElement("div");
	var hand = userToAdd.cards;
	var handImg = [];
	for(i=0;i<hand.length;i++)
	{
		var img = new Image();
		if ((i < 2) || showCards) {
			img.src = "../page-assets/cards/fronts/"+hand[i]+".png"; // has to be changed to what Kit has in the cardFaces bit
		}
		else {
			img.src = cardBack; // has to be changed to what Kit has in the cardFaces bit
		}
		img.width = (10/147) * height; // quarter of width of dealerCards size
		img.height = height * (2/21); // quarter of height of dealerCards size
		handImg.push(img); //  this would be looped until hand is full with all of the cards of that hand of that user
	}

	for(i=0;i<hand.length;i++)
	{
		playerCards.appendChild(handImg[i]);
	}
	player.style.border = "1px solid yellow";
	player.style.padding = "0 10px 10px 10px";
	player.style.marginBottom = "10px";
	player.appendChild(playerCards);
	divLeft.appendChild(player);
	divLeft.style.display = 'inline';
	resizeDivs();
}

function emptyUserList() {
	var userList = document.getElementById("userList");
	while (userList.hasChildNodes()) {
		userList.removeChild(userList.firstChild);
	}
}
//////////////////////////////////////////DEALER///////////////////////////////////////////////////////


function updateDealerCards(cardsSentByWS)
{
	for(var i = 0; i<cardsSentByWS.length; i++){
		var img = new Image();
		var h = window.innerHeight;
		h = h / 3.5;
		img.src = '../page-assets/cards/fronts/'+cardsSentByWS[i]+'.png';
		img.width = (5/7) * h;
		img.height = h;
		dealerCards.push(img);
	}
}


function printDealerPoints(value) {
	document.getElementById("dealerPoints").innerHTML = value;
}


function displayDealerCards(){
	var h = window.innerHeight;
	document.getElementById("dealerDisplay").style.bottom = (-1) * (h/3) + "px"; // display bottom 2/3 of the card
	for(var i =0; i<dealerCards.length; i++){
		document.getElementById("dealerDisplay").appendChild(dealerCards[i]);
	}

	var div = document.createElement("div");
	div.setAttribute("id", "dealerPoints");
	document.getElementById("dealerDisplay").appendChild(div);
}


function resetDealerCards()
{
	var dealerDisp = document.getElementById("dealerDisplay");
	while (dealerDisp.hasChildNodes()) {
	  dealerDisp.removeChild(dealerDisp.firstChild);
	}

	for(i = dealerCards.length - 1; i >= 0; i--){
		dealerCards.pop();
	}
}


function processDealerCards(msg){
	console.log(msg);
	resetDealerCards();
	console.log(msg.cards);
	updateDealerCards(msg.cards);
	displayDealerCards();
	printDealerPoints("Dealer Points: " + msg.points);
	centreCards();
}

//////////////////////////BUTTONS/////////////////////////////////////////////////////////////////

function hit(){
  var msg = {
    type: "action",
		responseLevel: 2,
    action: "Hit",
    username: username,
    roomUsername: roomUsername
  }
  ws.send(JSON.stringify(msg)); //send player_username;
}


function stand(){
  var msg = {
    type: "action",
		responseLevel: 2,
    action: "Stand",
    username: username,
    roomUsername: roomUsername
  }
  ws.send(JSON.stringify(msg)); //send player_username;
}


//check if u are the host
function startGame() {
  document.getElementById("startGame").style.display = "none";
  var msg = {
    type: "startGame",
		responseLevel: 2,
    roomUsername: roomUsername
  }
  ws.send(JSON.stringify(msg));
}


//only for host check it locally
function ready() { //new round button
    var msg = {
    type: "readyForRound",
		responseLevel: 2,
    roomUsername: roomUsername
  }
  ws.send(JSON.stringify(msg));
}


function enableButtons(){
  document.getElementById("hit").disabled = false;
  document.getElementById("stand").disabled = false;
  document.getElementById("startGame").hidden = true;
  document.getElementById("quitGame").hidden = false;
	console.log("enableButtons");
}


function disableButtons(){
  document.getElementById("hit").disabled = true;
  document.getElementById("stand").disabled = true;
  document.getElementById("startGame").hidden = true;
  document.getElementById("quitGame").hidden = true;
	console.log("disableButtons");
}

//////////////////////////////////LEADERBOARD///////////////////////////////////////////////////////////

function createLeaderboardTable(array){
	var table = document.createElement("table");
	var header = table.createTHead();
	var row = header.insertRow(0);
	var cell = row.insertCell(0);
	cell.innerHTML = "<b>Username</b>";
	var cell2 = row.insertCell(1);
	cell2.innerHTML = "<b>Points won</b>";
	
	for(var i = 0; i< array.length; i++){
		let row = table.insertRow();

		let cell1 = row.insertCell();
		let text1 = document.createTextNode(array[i].username);
		cell1.appendChild(text1);

		let cell2 = row.insertCell();
		let text2 = document.createTextNode(array[i].score);
		cell2.appendChild(text2);
	}
	table.setAttribute("id", "leaderboard");

	clearMessageSpot();
	var height = window.innerHeight;
	var messageSpot = document.getElementById("messageSpot");
	resizeDivs();
	messageSpot.style.display = "inline-block";

	messageSpot.appendChild(table);
	var disclaim = document.createElement("p");
	disclaim.innerText = "3 or more points counts as a win in your stats \nYou get 20 coins per point ";
	messageSpot.appendChild(disclaim);
	//table.style.marginLeft = "47.5%";
}

function clearMessageSpot(){
	var lb = document.getElementById("messageSpot");
	while (lb.hasChildNodes()) {
	  lb.removeChild(lb.firstChild);
	}
}

//////////////////////////ENDGAME////////////////////////////////////////////////////////////

function endGame(){
	disableButtons();
}

//////////////////////////WEB SOCKET STATUS ///////////////////////////////////////////////

function setStatus(message){
	document.getElementById("status").innerHTML = message;
}

/////////////////////////MESSAGES////////////////////////////////////////////////

function updateWaitingMessage(message){
	if (message == "") {
		hideMessage();
		return
	}
	var messageSpot = document.getElementById("messageSpot");
	messageSpot.style.display = "inline";
	messageSpot.innerHTML = message;
	var height = window.innerHeight;
	resizeDivs();
	messageSpot.style.display = "inline-block";

}

function hideMessage()
{
	document.getElementById("messageSpot").style.display = "none";
}


/////////////////////////CONNECTIONS////////////////////////////////////////////////

function leaveRoom() {
		window.location.replace("../home/home.php");
}
	function disconnectFromGame(){
		var msg = {
			type: "leaveRoom",
			responseLevel: 1,
			username: username,
			roomUsername: roomUsername
		}
		ws.send(JSON.stringify(msg));
	}
///////////////////////////RECEIVING MSG FROM WEB SOCKET ///////////////////////////////////
ws.onopen = function (){
  setStatus("ONLINE");
  msg = {
    type: "establishConnection",
		responseLevel:1,
    username: username,
    roomUsername: roomUsername
  }
  ws.send(JSON.stringify(msg));
}
ws.onclose = () => setStatus("DISCONNECTED");


//do not let user connect back to the game

ws.onmessage = function (response) {
  var msg = JSON.parse(response.data);
  switch (msg.type) {
		// case "createRoomResponse": // level NO NEED //BLACKJAKC INTERFACE
		// //after the room is created, it sends back the host username
		// 	roomUsername = msg.roomUsername;
		// 	break;
		//
		// case "joinRoomResponse": //level NO NEED //BLACKJAKC INTERFACE
		// //after the user joins the room the user receives the host username
		// 	roomUsername = msg.roomUsername;
		// 	break;
		case "playerJoin":
			processUserList(msg.playerList, false);
			break;
		case "text":
		//chatbox
			message = msg.text;
			if(message=="not connected to a room" || message=="game in progress"){
				leaveRoom();
			}
			break;

    case "startGameResponse": //LEVEL NO NEED
      if (msg.gameStarted) {
				//game has started - is running
				//change message from waiting for host to playing
				updateWaitingMessage('start'); //finish
        enableButtons();
      }
      break;

		case "hostLeft":
			leaveRoom();
		break;

		case "leaveRoomInfo": //level 2
		//update list of players
			//dealerCards = msg.dealerCards; //array of dealers cards
			listOfPlayers = msg.playerCards; //array of all players
			msg.playerLeft;
			msg.dealerCards;
			msg.playersCards
			getMyInfo(msg.playersCards);
			
			processUserList(msg.playersCards, false); //finish
			//[{username, cards, points, won}, {username, cards, points, won}]
			break;

		// case "readyForRoundResponse": //undefined in WebSocket
		//   //ws sends that the game has ended and it is ready for new round
		// 	//remove waiting message show cards
		// 	processUserCards();
		//   break;

    case "cardsInfo": //level 2
		//ws sends info about this user
		//msg cards, points
		processUserList(msg.playersCards, false);
		processUserCards(getMyInfo(msg.playersCards));
		resetBorders();
		playersTurnBorder(currentlyPlaying);
		//update player list
		console.log("successfully printed user cards");
    break;

    case "cardsInfoDealer": //level NONE
		//ws sends infor about dealer
		//msg cards, points
			processDealerCards(msg);
			console.log("successfully printed dealer cards");
      break;

		case "turnsInfo": //level 2
			//sends back the username of the user whose turn it is
			currentlyPlaying = msg.currentUsername; //this user is currently playing
			resetBorders();
			playersTurnBorder(currentlyPlaying);
			console.log("player that plays now: " + currentlyPlaying);
			break;

		case "newRoundCardsInfo": //level 2
			//new round
			document.getElementById("ready").style.display = "none";
			processUserList(msg.playersCards, false);
			processUserCards(getMyInfo(msg.playersCards));
			processDealerCards(msg.dealerCards);
			updateWaitingMessage('');
			resetBorders();
			playersTurnBorder(msg.playersCards[0].username);
			console.log("successfully printed user cards new round");
			break;

	  case "endRoundInfo": //level 2
			processUserList(msg.playersCards, true);
	    //ends round, prints leaderboard
			won = getMyInfo(msg.playersCards).won;
			points = getMyInfo(msg.playersCards).points;
			 //playersCards contains username, cards, points, won
			 updateWaitingMessage('WAITING FOR HOST TO START NEW ROUND');
			processDealerCards(msg.dealerCards); //contains an array of dealers' cards
			console.log("successfully printed user cards end round");
			if (username == roomUsername) {
				document.getElementById("ready").style.display = "inline";
			}
			break;

		case "endGame": //level ?
			//ends the game, should show the user leaderboard and disable buttons
			//array of objects: {username, score}
			createLeaderboardTable(msg.leaderboard);
			document.getElementById("ready").style.display = "none";
			endGame();
			break;
			
		case "connectionSuccess":
			if (!msg.success) {
				leaveRoom();
			}
			break;
			
		case "chatReceive":
			addMessage(msg.text);
			break;

  }
}
