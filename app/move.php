<?php
namespace app;
session_start();

include_once 'HiveGame.php';
include_once 'db/database.php';

$database = new Database();
$game = HiveGame::fromSession($database, $_SESSION);
$from = $_POST['from'];
$to = $_POST['to'];

$board = $game->getBoard();

unset($_SESSION['error']);

try {
    $moveId = $game->move($from, $to);

    $_SESSION['player'] = $game->getOtherPlayer();
    $_SESSION['last_move'] = $moveId;
    $_SESSION['board'] = $board->toArray();
    $_SESSION['hand'] = array_map(function (PlayerHand $hand) {
        return $hand->getHand();
    }, $game->getHands());
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
} finally {
    header('Location: index.php');
}

