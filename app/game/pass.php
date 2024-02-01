<?php

use Main\Database;

session_start();

$board = $_SESSION['board'];
$player = $_SESSION['player'];
$hand = $_SESSION['hand'][$player];

$db = new Database();
$game = new HiveGame($hand, $board, $player);

$stmt = $db->getCon()->prepare(
    'insert into moves (game_id, type, move_from, move_to, previous_id, state) 
            values (?, "pass", null, null, ?, ?)'
);
$state = $game->getState();
$stmt->bind_param('iis', $_SESSION['game_id'], $_SESSION['last_move'], $state);
$stmt->execute();
$_SESSION['last_move'] = $db->insert_id;
$_SESSION['player'] = 1 - $_SESSION['player'];

header('Location: index.php');
