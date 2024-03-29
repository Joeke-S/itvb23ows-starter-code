<?php
namespace app;

session_start();

include_once 'HiveGame.php';
include_once 'db/database.php';
include_once 'PlayerHand.php';

$database = new Database();
$game = new HiveGame($database);

$_SESSION['board'] = $game->getBoard()->toArray();
$players = [new Player(0), new Player(1)];
$_SESSION['players'] = $players;
$_SESSION['hand'] = [0 => $players[0]->getHandArr(), 1 => $players[0]->getHandArr()];
$_SESSION['player'] = $game->getPlayer();
$_SESSION['game_id'] = $game->getGameId();

header('Location: index.php');