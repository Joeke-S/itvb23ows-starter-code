<?php
namespace app;

session_start();
include_once 'HiveGame.php';
include_once 'db/database.php';
include_once 'PlayerHand.php';

$database = new Database();
$game = new HiveGame($database);

$_SESSION['board'] = $game->getBoard()->toArray();
$_SESSION['hand'] = array_map(function (PlayerHand $hand) {
    return $hand->getHand();
}, $game->getHands());
$_SESSION['player'] = $game->getPlayer();
$_SESSION['game_id'] = $game->getGameId();

header('Location: index.php');