<?php

namespace app;

session_start();
include_once 'util.php';
include_once 'HiveGame.php';
include_once 'GamePrinter.php';
include_once 'db/database.php';

if (!isset($_SESSION['board'])) {
    header('Location: restart.php');
    exit;
}


$database = new Database();
$game = HiveGame::fromSession($database, $_SESSION);

$gamePrinter = new GamePrinter($game);

$WHITE = 0;
$BLACK = 1;


?>
<!DOCTYPE html>
<html>
<head>
    <title>Hive</title>
    <link rel="stylesheet" type="text/css" href="style/style.css">
</head>
<body>
<h1>Welcome to the Hive game!</h1>
<div class="board">
    <?php
        $gamePrinter->printBoard();
    ?>
</div>
<div class="hand">
    White:
    <?php
        $gamePrinter->printHand($WHITE);
    ?>
</div>
<div class="hand">
    Black:
    <?php
        $gamePrinter->printHand($BLACK);

    ?>
</div>
<div class="turn">
    Turn:
    <?php
        if ($game->getPlayer() == $WHITE) {
            echo "White";
        } else {
            echo "Black";
        }
    ?>
</div>
<form method="post" action="play.php">
    <select name="piece">
        <?php
            $gamePrinter->printPiecesAvailable();
        ?>
    </select>
    <select name="to">
        <?php
            $gamePrinter->printPlayTo();
        ?>
    </select>
    <input type="submit" value="Play">
</form>
<form method="post" action="move.php">
    <select name="from">
        <?php
            $gamePrinter->printMoveFrom($game);

        ?>
    </select>
    <select name="to">
        <?php
            $gamePrinter->printMoveTo($game);
        ?>
    </select>
    <input type="submit" value="Move">
</form>
<form method="post" action="pass.php">
    <input type="submit" value="Pass">
</form>
<form method="post" action="restart.php">
    <input type="submit" value="Restart">
</form>
<strong>
    <?php
    if (isset($_SESSION['error'])) {
        echo $_SESSION['error'];
    }
    unset($_SESSION['error']);
    ?></strong>
<ol>
    <?php
    $gamePrinter->printMoveHistory();
    ?>
</ol>
<form method="post" action="undo.php">
    <input type="submit" value="Undo">
</form>
</body>
</html>

