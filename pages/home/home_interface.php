<?php
  $g1GamesPlayed = "N/A";
  $g1GamesWon = "N/A";
  $g1GamesLost = "N/A";
  $g1WinStreak = "N/A";

  $g2GamesPlayed = "N/A";
  $g2jGamesWon = "N/A";
  $g2jGamesLost = "N/A";
  $g2jWinStreak = "N/A";

  $g3GamesPlayed = "N/A";
  $g3GamesWon = "N/A";
  $g3GamesLost = "N/A";
  $g3WinStreak = "N/A";

  $g1Name = NULL;
  $g1Desc = NULL;
  $g1MaxP = NULL;
  $g1MinP = NULL;

  $g2Name = NULL;
  $g2Desc = NULL;
  $g2MaxP = NULL;
  $g2MinP = NULL;

  $g3Name = NULL;
  $g3Desc = NULL;
  $g3MaxP = NULL;
  $g3MinP = NULL;

  if (isset($_SESSION['username'])) {
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
    //connection successful
    $sessionUser = $_SESSION['username'];

    //GET STATS
    $QueryUsers = "SELECT statistics.*
                   FROM statistics
                   WHERE statistics.username = '$sessionUser'";

    $result = mysqli_query($connect, $QueryUsers);
    if($result) {
      while($row = mysqli_fetch_assoc($result)) {
        switch($row['gameID']) {
          case(1):
            $g1GamesWon = $row['win'];
            $g1GamesLost = $row['lose'];
            $g1WinStreak = $row['streak'];
            $g1GamesPlayed = $g1GamesWon + $g1GamesLost;
          break;
          case(2):
            $g2GamesWon = $row['win'];
            $g2GamesLost = $row['lose'];
            $g2WinStreak = $row['streak'];
            $g2GamesPlayed = $g2GamesWon + $g2GamesLost;
          break;
          case(3):
            $g3GamesWon = $row['win'];
            $g3GamesLost = $row['lose'];
            $g3WinStreak = $row['streak'];
            $g3GamesPlayed = $g3GamesWon + $g3GamesLost;
          break;
        }
      }
    }

    //GET GAMES
    $QueryUsers = "SELECT gamemode.*
                   FROM gamemode";

    $result = mysqli_query($connect, $QueryUsers);
    if($result) {
      while($row = mysqli_fetch_assoc($result)) {
        switch($row['gameID']) {
          case(1):
            $g1Name = $row['gamename'];
            $g1Desc = $row['description'];
            $g1MaxP = $row['maxplayers'];
            $g1MinP = $row['minplayers'];
          break;
          case(2):
            $g2Name = $row['gamename'];
            $g2Desc = $row['description'];
            $g2MaxP = $row['maxplayers'];
            $g2MinP = $row['minplayers'];
          break;
          case(3):
            $g3Name = $row['gamename'];
            $g3Desc = $row['description'];
            $g3MaxP = $row['maxplayers'];
            $g3MinP = $row['minplayers'];
          break;
        }
      }
    }
  }
 ?>
