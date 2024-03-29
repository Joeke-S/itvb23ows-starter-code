<?php
namespace app;
session_start();
include_once 'HiveGame.php';
include_once 'db/database.php';

$database = new Database();
$game = new HiveGame($database);

$game->undo($_SESSION['last_move']);

//header('Location: index.php');

