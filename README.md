# Decked Out
First year team project in collaboration with other 5 people
Decked Out - a web application that simulates playing Blackjack, where a user can play with other people.
 
## Tech stack:
 - Frontend: Websockets | HTML | CSS | Javascript
 - Backend: PHP | NodeJS | SQL

## Description
Web application where the user can play simulated Blackjack
- the implementation on the frontend uses Javascript which communicates with PHP and Node on the backend
- PHP is used for database queries - updating data, and fetching data
- Node is used on the backend to host a websocket server in Javascript - this is the game logic
- the Websocket server connects users to play the same instance of a Blackjack game

## App demo
- the user has their own profile with artificial currency, with which they can use to buy card skins
<img src='https://github.com/user-attachments/assets/49057784-338b-4ff2-a517-031c1f5843e8' width=80% />

- in Blackjack they play against the dealer, and try to beat them - the win is determied on the best of 3 matchup
  - if they win 2 times, it is considered as a win
- there is also a chat box they can use to communicate with others
<img src='https://github.com/user-attachments/assets/615d4e5c-d61a-4dbe-a657-9c85af3d7e0b'  width=80% />
