<?php
    include("../page-assets/session_validation.php");
    include("entry_validation.php");

    $insertGameID = "INSERT INTO gameRoom (username, currentNoPlayers, gameID, gameEnd)
                     VALUES ('$username', 1, 1, 0)";
    $result = mysqli_query($connect, $insertGameID);
    if (!$result){
      die('cannot insert game room');
    }
    $_SESSION['hostname'] = $_POST['hostname'];
    header("Location:../game/game.php");
    die("");
?>
