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
    $_SESSION['board'] = $game->getBoard()->toArray();
    $_SESSION['player'] = $game->getOtherPlayer();
    $_SESSION['last_move'] = $moveId;

    $_SESSION['players'] = $game->getPlayers();
    $_SESSION['hand'] = [0 => $game->getOneOfPlayers(0)->getHandTokens(), 1 => $game->getOneOfPlayers(1)->getHandTokens()];
} catch (GameException $e) {
    $_SESSION['error'] = $e->getMessage();
} finally {
    header('Location: index.php');
}
