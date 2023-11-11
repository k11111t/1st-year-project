class GameRoom {
		
		static gameList = [];
		
		static deck = ["2S", "3S", "4S", "5S", "6S", "7S", "8S", "9S", "10S", "jS", "qS", "kS", "aS",
					   "2D", "3D", "4D", "5D", "6D", "7D", "8D", "9D", "10D", "jD", "qD", "kD", "aD", 
					   "2C", "3C", "4C", "5C", "6C", "7C", "8C", "9C", "10C", "jC", "qC", "kC", "aC",
					   "2H", "3H", "4H", "5H", "6H", "7H", "8H", "9H", "10H", "jH", "qH", "kH", "aH"];
		
		static autoIncrementCounter = 0;

		
		constructor(gameType, roomUsername, minNoPlayers, maxNoPlayers) {
			this.gameId = GameRoom.autoIncrementCounter++;
			this.roomUsername = roomUsername;
			//this.usernameList = [roomUsername];
			this.gameType = gameType;
			this.minNoPlayers = minNoPlayers;
			this.maxNoPlayers = maxNoPlayers;
			this.currentDeck = GameRoom.generateDeck();
			this.playerList = [];
			this.clientList = [];
			this.canStart = (this.playerList.length >= this.minNoPlayers);
			this.indexOfPlayerPlaying = 0;
			this.currentDeck = GameRoom.shuffleDeck(this.currentDeck);
			this.isRunning = false;
			//this.playerPlaying = playerList[0];
			this.dealerHand = {
				cards: [],
				points: 0,
				soft: -1
			};
			this.round = 0;
			
		}
		
		addClient(client) {
			this.clientList.push(client);
		}
		
		removeClient(client){
			var index = this.clientList.indexOf(client);
			this.clientList = this.clientList.splice(0, index).concat(this.clientList.splice(index+1));
		}
		
		updateCanStart(){
			
			this.canStart = (this.playerList.length >= this.minNoPlayers);
		}
		
		
		static getRoomByHostUsername(roomUsername){
			
			for (var i = 0; i < GameRoom.gameList.length; i++) {
				var room = GameRoom.gameList[i];
				if (room.roomUsername == roomUsername) {
					return room; 
				}
			}
			
			return null;
		}
		
		currentNoPlayers(){
			return this.playerList.length;
		}
		
		
		static generateDeck() {
			return [   "2S", "3S", "4S", "5S", "6S", "7S", "8S", "9S", "10S", "jS", "qS", "kS", "aS",
					   "2D", "3D", "4D", "5D", "6D", "7D", "8D", "9D", "10D", "jD", "qD", "kD", "aD", 
					   "2C", "3C", "4C", "5C", "6C", "7C", "8C", "9C", "10C", "jC", "qC", "kC", "aC",
					   "2H", "3H", "4H", "5H", "6H", "7H", "8H", "9H", "10H", "jH", "qH", "kH", "aH", 
					   
					   "2S", "3S", "4S", "5S", "6S", "7S", "8S", "9S", "10S", "jS", "qS", "kS", "aS",
					   "2D", "3D", "4D", "5D", "6D", "7D", "8D", "9D", "10D", "jD", "qD", "kD", "aD", 
					   "2C", "3C", "4C", "5C", "6C", "7C", "8C", "9C", "10C", "jC", "qC", "kC", "aC",
					   "2H", "3H", "4H", "5H", "6H", "7H", "8H", "9H", "10H", "jH", "qH", "kH", "aH"];
		}
		
		//shuffling
		static shuffleDeck(deckIn) {
			for (let i = deckIn.length -1; i >0; i--) {
				const j = Math.floor(Math.random() * (i + 1));
				[deckIn[i], deckIn[j]] = [deckIn[j], deckIn[i]];
			}
			return deckIn;
		}
		
		getNextPlayer(){
			this.indexOfPlayerPlaying++;
			//console.log(this.indexOfPlayerPlaying);
			if (this.indexOfPlayerPlaying == this.playerList.length){
				this.endRound();
			}
			else if(this.indexOfPlayerPlaying < this.playerList.length){
				var message = {
					type: "turnsInfo",
					currentUsername: this.playerList[this.indexOfPlayerPlaying].username
				}
				
				var responseAccess = {
					responseLevel: 2,
					roomUsername: this.roomUsername
				}
				
				resendMessage(null, message, responseAccess); //here
			}
		}
		
		newRound(){
			this.round++;
			this.indexOfPlayerPlaying = 0;
			var playersCards = [];
			for (var i = 0; i < this.playerList.length; i++) {
				//var player = this.playerList[i];
				this.playerList[i].hand = {
					cards: [],
					points: 0,
					soft: -1,
					busted: false,
					won: -1
				}
				this.playerList[i].giveCard();
				this.playerList[i].giveCard();
				this.playerList[i].ifBusted();
				playersCards.push(
					{
						username: this.playerList[i].username,
						cards: this.playerList[i].hand.cards,
						points: this.playerList[i].hand.points					
					}
				);
				
			}
			
			
			this.initialiseDealer();
			
			//message to send turns
			var message = {
				type: "turnsInfo",
				currentUsername: this.playerList[this.indexOfPlayerPlaying].username
			}
			
			var responseAccess = {
				responseLevel: 2,
				roomUsername: this.roomUsername
			}
			
			resendMessage(null, message, responseAccess); //here
			
			message = {
				type: "newRoundCardsInfo",
				dealerCards: this.dealerHand,
				playersCards: playersCards
			}
			
			responseAccess = {
				responseLevel: 2,
				roomUsername: this.roomUsername
			}
			
			resendMessage(null, message, responseAccess);
		}
		
		endRound(){
			while (this.dealerHand.points < 17) {
				this.giveDealerCard();
				if (this.dealerHand.points == 17 && this.dealerHand.soft == 1){this.giveDealerCard;}
			}
			
			var playersCards = [];
			
			for (var i = 0; i < this.playerList.length; i++){
				//var player = this.playerList[i];

				if (!this.playerList[i].hand.busted){
					if (this.dealerHand.points > 21) { //if dealer is busted
						this.playerList[i].hand.won = 1;
					} else if (this.playerList[i].hand.points > this.dealerHand.points) {
						this.playerList[i].hand.won = 1;
					} else if (this.playerList[i].hand.points == this.dealerHand.points) {
						this.playerList[i].hand.won = 0;
					} else {
						this.playerList[i].hand.won = -1;
					}
				} else {
					this.playerList[i].hand.won = -1;
				}
				
				
				// 1 - won; 0 - draw; -1 - lost
				if (this.playerList[i].hand.won == 1) {
					this.playerList[i].roundWins += 2;
				}
			   if(this.playerList[i].hand.won == 0) {
					this.playerList[i].roundWins += 1;
				}
				
				playersCards.push(
					{
						username: this.playerList[i].username,
						cards: this.playerList[i].hand.cards,
						points: this.playerList[i].hand.points,
						won: this.playerList[i].hand.won					
					}
				);
			}
			
			
			var message = {
				type: "endRoundInfo",
				dealerCards: this.dealerHand,
				playersCards: playersCards
			}
			
			//console.log(message);
			
			var responseAccess = {
				responseLevel: 2,
				roomUsername: this.roomUsername
			}
			
			resendMessage(null, message, responseAccess); //here
			
			if (this.round == 3) {this.endGame();}
		}
		
		
		endGame(){
			
			var leaderboard = [];
			for (var i = 0; i < this.playerList.length; i++){
				leaderboard.push({username: this.playerList[i].username,
								  score: this.playerList[i].roundWins});
			}
			
			
			message = {
				type: "endGame",
				leaderboard: leaderboard
			}
			
			var responseAccess = {
				responseLevel: 2,
				roomUsername: this.roomUsername
			}
			resendMessage(null, message, responseAccess);
			
			for(var i=0; i<this.clientList.length; i++){
				this.clientList[i].terminate();
			}
			
			//sql query
			updateUsersCurrency(leaderboard);
			updateStatistics(this.playerList);			
			GameRoom.removeGameRoom(this);
		}
		
		
		//dealer logic
		giveDealerCard() {
			var card = this.currentDeck.pop();
			
			this.dealerHand.cards.push(card);
			this.updateDealerPoints();

		}
		
		updateDealerPoints(){
			var cardList = this.dealerHand.cards;
			
			var tempCount = 0;
			var aceCount = 0;
			var soft = -1;
			
			
			for (var i = 0; i < cardList.length; i++){
				var card = cardList[i];
				if ((card.charAt(0) == "1") || (card.charAt(0) == "j") || (card.charAt(0) == "q") || (card.charAt(0) == "k")){
					tempCount += 10;
				} else if (card.charAt(0) == "a"){
					soft = 0;
					aceCount += 1;
				} else {
					tempCount += Number(card.charAt(0));
				}
			}
			while (true) {
				if (aceCount > (21 - tempCount)) {
					tempCount += aceCount;
					break;
				} else if ((aceCount > 0) && ((aceCount - 1) <= (10 - tempCount))){
					soft = 1;
					aceCount -= 1;
					tempCount += 11;
				} else {
					tempCount += aceCount;
					break;
				}
			}
			
			this.dealerHand.points = tempCount;
			this.dealerHand.soft = soft;
		}
		
		static removeGameRoom(gameRoom){
			var index = GameRoom.gameList.indexOf(gameRoom);
			GameRoom.gameList = GameRoom.gameList.splice(0, index).concat(GameRoom.gameList.splice(index+1));
			//GameRoom.gameList.splice(GameRoom.gameList.indexOf(gameRoom), 1);
		}

		resendDealerCards(onlyOne){
			if (onlyOne){
				var msg = {
					type: "cardsInfoDealer",
					cards: dealerHand.cards[0],
					points: null
				}
			} else {
				var msg = {
					type: "cardsInfoDealer",
					cards: dealerHand.cards,
					points: dealerHand.points
				}
			}
			server.clients.forEach(function (client) {
				if(client.readyState == WebSocket.OPEN){
					client.send(JSON.stringify(msg)); 
				}
			});
			
		}

		initialiseDealer(){
			this.dealerHand = {
				cards: [],
				points: 0,
				soft: -1
			}
			this.giveDealerCard();
			this.giveDealerCard();
			//resendDealerCards(onlyOne = true);
			
		}

		
		
		
}


class Player{

		/*static playerObjectList = [];*/
		
		constructor(currency, username, roomUsername){
			this.currency = currency; //request from DB
			this.username = username;
			this.gameRoom = GameRoom.getRoomByHostUsername(roomUsername);
			this.turn = (username == roomUsername);
			this.hand = {
				cards: [],
				points: 0,
				soft: -1,
				busted: false,
				won: -1
			}
			this.roundWins = 0;
			/*playerObjectList.push(this);*/
		}
		
		leaveRoom(client){ 
			var currentTurn = this.gameRoom.indexOfPlayerPlaying;
			var leaverTurn = this.gameRoom.playerList.indexOf(this);
			
			var message = null;
			var responseAccess = {
				responseLevel: 2,
				roomUsername: this.gameRoom.roomUsername
			}
			
			
			if (this.username == this.gameRoom.roomUsername) {
				message = {
					type: "hostLeft"
				}
				resendMessage(null, message, responseAccess);
				GameRoom.removeGameRoom(this.gameRoom);
				
			} else {
				//remove from player list and client list
				this.gameRoom.playerList = this.gameRoom.playerList.splice(0, leaverTurn).concat(this.gameRoom.playerList.splice(leaverTurn+1));
				this.gameRoom.removeClient(client);
				
				this.gameRoom.indexofPlayerPlaying--;
				if (currentTurn == leaverTurn){
					this.gameRoom.getNextPlayer();
				}
				
				var playersCards = [];
				for (var i = 0; i < this.gameRoom.playerList.length; i++) {
					//var player = this.gameRoom.playerList[i];
					playersCards.push(
						{
							username: this.gameRoom.playerList[i].username,
							cards: this.gameRoom.playerList[i].hand.cards,
							points: this.gameRoom.playerList[i].hand.points,
							won: this.gameRoom.playerList[i].hand.won					
						}
					);
				}
				
				//if not - remove the player
				// playersCards
				message = {
					type: "leaveRoomInfo",
					playerLeft: this.username,
					dealerCards: this.gameRoom.dealerHand.cards,
					playersCards: playersCards
				}
				
				resendMessage(null, message, responseAccess);
			}
		}
		
		recieveHit(){
			this.giveCard();
			var playersCards = [];
			for(var i = 0; i<this.gameRoom.playerList.length; i++){
				playersCards.push(
				{username: this.gameRoom.playerList[i].username,
				 cards: this.gameRoom.playerList[i].hand.cards,
				 points: this.gameRoom.playerList[i].hand.points
				});
			}
			console.log("HIT");
			console.log(playersCards);
			
			var message = {
				type: "cardsInfo",
				playersCards: playersCards
			}
			
			var responseAccess = {
				responseLevel: 2,
				roomUsername: this.gameRoom.roomUsername
			}
			
			resendMessage(null, message, responseAccess);
			
			this.ifBusted();
			
		}
		
		recieveStand(){
			//resendCards
			this.gameRoom.getNextPlayer();
		}
		
		giveCard(){
			var card = this.gameRoom.currentDeck.pop();
			this.hand.cards.push(card);
			this.updatePoints();
		//	resendCards(this.hand); //here
			//this.ifBusted();
		}

		ifBusted(){
			if (this.hand.points > 21){
				//resendCards(currentRound);
				this.hand.busted = true;
				this.gameRoom.getNextPlayer();
			} 
		}

		
		updatePoints(){
			var cardList = this.hand.cards;
			
			var tempCount = 0;
			var aceCount = 0;
			var soft = -1;
			
			
			for (var i = 0; i < cardList.length; i++){
				var card = cardList[i];
				if ((card.charAt(0) == "1") || (card.charAt(0) == "j") || (card.charAt(0) == "q") || (card.charAt(0) == "k")){
					tempCount += 10;
				} else if (card.charAt(0) == "a"){
					soft = 0;
					aceCount += 1;
				} else {
					tempCount += Number(card.charAt(0));
				}
			}
			while (true) {
				if (aceCount > (21 - tempCount)) {
					tempCount += aceCount;
					break;
				} else if ((aceCount > 0) && ((aceCount - 1) <= (10 - tempCount))){
					soft = 1;
					aceCount -= 1;
					tempCount += 11;
				} else {
					tempCount += aceCount;
					break;
				}
			}
			
			this.hand.points = tempCount;
			this.hand.soft = soft;
		}

		
		
}








//"use strict";

/*const http = require("http");
const server1 = http.createServer();

server1.on("request", function(req, res){
	
	});
	
server1.listen(3500, "10.2.239.255");
*/
 
const WebSocket = require("ws");

const server = new WebSocket.Server({port : 3000});


var sql = require('mysql');
var Filter = require('bad-words'),
 filter = new Filter();
var async = require('async');


// config for your database

var con = sql.createConnection({
    user: 'h21817ja',
    password: 'dbp455wrd',
    host: 'dbhost.cs.man.ac.uk', 
    database: '2019_comp10120_y8' 
});

con.connect(function (err) {
	if (err) console.log(err);
});

 // s - spades
 //h - hearts
 // c clubs
  //d - diamonds
  //2-10 j, q, k, a;


function resendText (message) {
	var msg = {
		type: "text",
		text: message
		
	}
	server.clients.forEach(function (client) {
		if(client.readyState == WebSocket.OPEN){
			client.send(JSON.stringify(msg)); 
		}
	});
}

/*function resendCards (cardList) {
	//playersCards = 
	
	var msg = {
		type: "cardsInfo",
		playersCards: playersCards
	}
	
	server.clients.forEach(function (client) {
		if(client.readyState == WebSocket.OPEN){
			client.send(JSON.stringify(msg)); 
		}
	});
}*/


function resendMessage(client, msg, responseAccess){
	/*responseAccess == {
		responseLevel: 1 or 2 or 3
		roomUsername: if 2
	}*/
	console.log("STAGE1");
	if (responseAccess.responseLevel == 1){
		console.log("STAGE2");
		server.clients.forEach(function (tempClient) {
			if((client === tempClient) && (tempClient.readyState == WebSocket.OPEN)){
				console.log("STAGE3");
				tempClient.send(JSON.stringify(msg)); 
			}
		});
	} else if (responseAccess.responseLevel == 2) {
		console.log("STAGE4");
		var gameIn = GameRoom.getRoomByHostUsername(responseAccess.roomUsername);	
		for (var i = 0; i < gameIn.clientList.length; i++){
			console.log("STAGE5");
			server.clients.forEach(function (tempClient) {
				
				if((gameIn.clientList[i] == tempClient) && (tempClient.readyState == WebSocket.OPEN)){
					tempClient.send(JSON.stringify(msg)); 
					console.log("sended");
				}
			});
		}
	} else if (responseAccess.responseLevel == 3) {
		
		server.clients.forEach(function (tempClient) {
			if(tempClient.readyState === WebSocket.OPEN){
				tempClient.send(JSON.stringify(msg)); 
			}
		});
	} else {}

}


//parser
server.on ("connection", function (ws, request, client) {
	server.clients.forEach(function (Tclient) {
		//console.log(Tclient);
	});
//	console.log("");
//	console.log(client);
//	console.log("");
//	console.log(ws);
	//console.log(server.clients.length);
	ws.on("message", function (message) {
		//message - message from USER in JSON STRING
		//msg - decoded message
		
		var msg = JSON.parse(message);
		
		if (msg.responseLevel == 2){
			var responseAccess = {
				responseLevel: 2,
				roomUsername: msg.roomUsername
			}
		} else {
			var responseAccess = {
				responseLevel: 1
			}
		}
				
		if (msg.type == "createRoom"){
			processCreateRoom(ws, msg, responseAccess);
			ws.terminate();
			return ;
		}
		console.log("WHICH MESSAGE");
		console.log(msg.type);
		console.log(msg.username);
		console.log(msg.roomUsername);	
		var gameroomIn = GameRoom.getRoomByHostUsername(msg.roomUsername);
		if (gameroomIn == null){
			resendText("not connected to a room");
		}
		else {
			switch (msg.type) {
				case "action":			
					processAction(ws, msg, responseAccess, gameroomIn); //add client
					break;
				case "joinRoom":
					processJoinRoom(ws, msg, responseAccess, gameroomIn);
					ws.terminate();
					break;
				case "startGame":
					processStartGame(ws, msg, responseAccess, gameroomIn);
					break;
				case "readyForRound":
					processReadyForNewRound(ws, msg, responseAccess, gameroomIn);
					break;
				case "establishConnection":
					processEstablishConnection(ws, msg, responseAccess, gameroomIn);
					break;
				case "chatSend":
					console.log("CASE CHAT SEND");
					processChat(ws, msg, responseAccess, gameroomIn);
					break;
			}
		}
	});
		
});

function processChat(client, msg, responseAccess, gameroomIn){
	console.log(msg.text);
	var text = filter.clean(msg.text);
	var message = {
		type:"chatReceive",
		text: msg.username + ": " + text
	};
	resendMessage(client, message, responseAccess);
	console.log(message.text);
}
	
function processLeaveRoom(client, msg, responseAccess, gameroomIn){
	for(var i = 0; i<gameroomIn.playerList.length; i++){
		if(gameroomIn.playerList[i].username == msg.username){
			gameroomIn.playerList[i].leaveRoom(client);
		}
	}
	
}

function processEstablishConnection(client, msg, responseAccess, gameroomIn){
	//gameroomIn = GameRoom.getGameRoomByUsername(gameroomIn);
	for (var i = 0; i < gameroomIn.playerList.length; i++) {
		if (gameroomIn.playerList[i].username == msg.username){
			gameroomIn.clientList.push(client);
			
			//send user list when user joins the room
			playerList = [];
			for(var j = 0; j<gameroomIn.playerList.length; j++){
				playerList.push({username: gameroomIn.playerList[j].username, cards: gameroomIn.playerList[j].hand.cards});
			}
			
			var message = {
				type:"playerJoin",
				playerList: playerList
			}
			var responseAccess = {
				responseLevel: 2,
				roomUsername: gameroomIn.roomUsername}
				
			resendMessage(client, message, responseAccess);
			
			if(gameroomIn.isRunning){
				
				processLeaveRoom(client, msg, responseAccess, gameroomIn);
				
				message = {
					type: "text",
					text: "game in progress"
				}
				
				var responseAccess = {
					responseLevel: 1,
					roomUsername: gameroomIn.roomUsername
				}
				
				resendMessage(client, message, responseAccess);
			}
			return;
		}
	}
	var message = {
		type: "connectionSuccess",
		success: false
	}		
	resendMessage(null, message, responseAccess);			
	client.terminate(); 
	//console.log('redirected user');
	
}

function processCreateRoom(client, msg, responseAccess) {
	
	//var game = new GameRoom(msg.gameType, msg.roomUsername, msg.minNoPlayers, msg.maxNoPlayers);
	GameRoom.gameList.push(new GameRoom(msg.gameType, msg.roomUsername, msg.minNoPlayers, msg.maxNoPlayers));
	var game = GameRoom.gameList[GameRoom.gameList.length-1];
	
	//var player = ; //currency, username, roomUsername

	game.playerList.push(new Player(1, msg.roomUsername, msg.roomUsername));
	game.clientList.push(client);
	game.updateCanStart();
	
	
	var message = {
		type: "createRoomResponse",
		roomUsername: msg.roomUsername,
	}
	
	
	resendMessage(client, message, responseAccess);
	game.removeClient(client);
	
	
	
}

//////////////////////DATABASE STUFF////////////////////////////////
function updateStatistics(playerList){
	async.forEachOf(playerList, function(element, index, inner_callback){
		var username = element.username;
		var getUserStats = "SELECT win, lose, streak FROM statistics WHERE username='"+username+"'";
		con.query(getUserStats, function(err, row, fields){
			if(err){
				console.log(err);
				return;
			}
			var usersWins = row[0].win;
			var usersLoss = row[0].lose;
			var usersStreak = row[0].streak;
			if(element.roundWins>2){
				usersStreak++;
				usersWins++;
			}else{
				usersStreak=0;
				usersLoss++;
			}
						
			var updateStats = "UPDATE statistics SET win="+usersWins+", lose="+usersLoss+", streak="+usersStreak+"  WHERE username='"+username+"'";
			con.query(updateStats, function(err, row, fields){
				if(err){
					console.log(err);
					return;
				}
			})
		})
	});
}


function updateUsersCurrency(leaderboard){
	async.forEachOf(leaderboard, function(element, index, inner_callback){
		var username = element.username;
		var score = element.score;
		var getUserBalance = "SELECT balance FROM bank WHERE username='"+username+"'";
		con.query(getUserBalance, function(err, row, fields){
			if(err){
				console.log(err);
				return;
			}
			var usersBalance = row[0].balance;
			usersBalance = usersBalance + 20*score;
			var updateCurrency = "UPDATE bank SET balance="+usersBalance+" WHERE username='"+username+"'";
			con.query(updateCurrency, function(err, row, fields){
				if(err){
					console.log(err);
					return;
				}
			})
		})
	});
}


function takeEntryFee(playerList){
	async.forEachOf(playerList, function(element, index, inner_callback){
		var username = element.username;
		var getUserBalance = "SELECT balance FROM bank WHERE username='"+username+"'";
		con.query(getUserBalance, function(err, row, fields){
			if(err){
				console.log(err);
				return;
			}
			var usersBalance = row[0].balance;
			usersBalance = usersBalance - 50;
			var updateCurrency = "UPDATE bank SET balance="+usersBalance+" WHERE username='"+username+"'";
			con.query(updateCurrency, function(err, row, fields){
				if(err){
					console.log(err);
					return;
				}
			})
		})
	});
}


function removeRoomDB(hostname){
		var deleteGame = "DELETE FROM gameRoom WHERE username='"+hostname+"'";
		con.query(deleteGame, function(err, response, fields){
			if (err) {
				console.log(err);
				console.log("COULD NOT DELETE ROOM");
				return;
			}
		});
}


function processJoinRoom(client, msg, responseAccess, gameroomIn) {
	//var player = ; //currency, username, roomUsername
	if(msg.username == msg.roomUsername){
		GameRoom.removeGameRoom(gameroomIn);
		gameroomIn.removeClient(client);
		var roomUsername = msg.roomUsername;
		var deleteGame = "DELETE FROM gameRoom WHERE username='"+roomUsername+"'";
		con.query(deleteGame, function(err, response, fields){
			if (err) {
				console.log(err);
				return;
			}
		});
		return;
	}
	gameroomIn.playerList.push(new Player(1, msg.username, msg.roomUsername));
	gameroomIn.clientList.push(client);
	gameroomIn.updateCanStart();
	
	var message = {
		type: "joinRoomResponse",
		roomUsername: msg.roomUsername,
	}
	
	
	resendMessage(client, message, responseAccess);
	gameroomIn.removeClient(client);
	
//	con.connect(function (err) {

    //if (err) console.log(err);
    //var roomUsername = gameroomIn.playerList[gameroomIn.playerList.length-1].roomUsername;
    var roomUsername = gameroomIn.roomUsername;
    var getCurrentNoPlayers = "SELECT currentNoPlayers FROM gameRoom WHERE username='" + roomUsername+"'";
    con.query(getCurrentNoPlayers, function(err, response, fields){
        if (err) {
            console.log(err);
            return null;
        }
        var NoPlayers = response[0].currentNoPlayers;
        if (NoPlayers != null){
            NoPlayers++;
            
            
            //get the maximum number of players
            var getMaxNoPlayers = "SELECT maxplayers FROM gamemode WHERE gameID=1";
            con.query(getMaxNoPlayers, function(err, response, fields){
                if (err) {
                    console.log(err);
                    return null;
                }
                
                if (response[0].maxplayers < NoPlayers) {
                    var deleteGame = "DELETE FROM gameRoom WHERE username='"+roomUsername+"'";
                    con.query(deleteGame, function(err, response, fields){
                        if (err) {
                            console.log(err);
                            return;
                        }
                    });
                } else {
                    var updateNoPlayers = "UPDATE gameRoom SET currentNoPlayers="+NoPlayers+" WHERE username='"+roomUsername+"'";
                    con.query(updateNoPlayers, function(err, response, fields){
                        if (err) {
                            console.log(err);
                            return;
                        }
                    });
                }
            });
        }
    });

//});

}

function processStartGame(client, msg, responseAccess, gameroomIn) {
	
	console.log("starts");
	if (gameroomIn.canStart){
		//sendstartgame
		//enablebuttons
		console.log(" can run starts");
		gameroomIn.isRunning = true;
		//delete row from database
		removeRoomDB(gameroomIn.roomUsername);
		//take 50 coins from each user
		takeEntryFee(gameroomIn.playerList);
	}
	console.log(GameRoom.getRoomByHostUsername(msg.roomUsername).isRunning);
	message = {
		type: "startGameResponse",
		gameStarted: gameroomIn.isRunning
	}
	
	resendMessage(client, message, responseAccess);
	gameroomIn.newRound();
}

function processReadyForNewRound(client, msg, responseAccess, gameroomIn){
	gameroomIn.newRound();
}


function processAction(client, msg, responseAccess, gameroomIn) {
	var action = msg.action;
	var player = null;
	for (var i = 0; i < gameroomIn.playerList.length; i++){
		if (gameroomIn.playerList[i].username == msg.username) {player = gameroomIn.playerList[i]; break;}
	}
	
	if (gameroomIn.playerList[gameroomIn.indexOfPlayerPlaying] == player){
		switch (action) {
			case "Hit":
				gameroomIn.playerList[i].recieveHit();
				break;
			case "Stand":
				gameroomIn.playerList[i].recieveStand();
				break;
		/*	case "leaveRoom":
				gameroomIn.playerList[i].leaveRoom(client);
				break;*/
	
		}
	} else {
		resendText("Not your turn");
	}
}



/*
 			if(gameroomIn.isRunning){
				var playersCards = [];
				for(var i = 0; i<gameroomIn.playerList.length; i++){
					playersCards.push(
					{username: gameroomIn.playerList[i].username,
					 cards: gameroomIn.playerList[i].hand.cards,
					 points: gameroomIn.playerList[i].hand.points
					});
				}
				
				var msg = {
					type:"cardsInfo",
					playersCards: playersCards
				}
				
				responseAccess = {
					responseLevel:1,
					roomUsername: gameroomIn.roomUsername
				}
				
				resendMessage(client, message, responseAccess);
				
				var msg = {
					type: "cardsInfoDealer",
					cards: gameroomIn.dealerHand.cards,
					points: gameroomIn.dealerHand.points
				}
				
				resendMessage(client, message, responseAccess);				
			}
			*/
