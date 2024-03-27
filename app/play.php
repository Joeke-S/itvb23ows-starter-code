<?php

namespace app;
session_start();

include_once 'HiveGame.php';
include_once 'db/database.php';

$database = new Database();
$game = HiveGame::fromSession($database, $_SESSION);
$piece = $_POST['piece'];
$to = $_POST['to'];


unset($_SESSION['error']);

try {
    $moveId = $game->play($piece, $to);
    $_SESSION['board'] = $game->getBoard()->getBoard();
    $_SESSION['player'] = $game->getOtherPlayer();
    $_SESSION['last_move'] = $moveId;
    $_SESSION['hand'] = array_map(function (PlayerHand $hand) {
        return $hand->getHand();
    }, $game->getHands());
} catch (GameException $e) {
    $_SESSION['error'] = $e->getMessage();
} finally {
    header('Location: index.php');
}
