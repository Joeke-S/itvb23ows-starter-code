<?php

use Main\Database;

session_start();

$board = $_SESSION['board'];
$player = $_SESSION['player'];
$hand = $_SESSION['hand'][$player];

$db = new Database();
$game = new HiveGame($hand, $board, $player);

$stmt = $db->getCon()->prepare('SELECT * FROM moves WHERE id = ' . $_SESSION['last_move']);
$stmt->execute();
$result = $stmt->get_result()->fetch_array();
$_SESSION['last_move'] = $result[5];
$game->setState($result[6]);
header('Location: index.php');
