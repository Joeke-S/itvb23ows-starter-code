<?php
namespace app;

include_once 'HiveGame.php';
include_once 'db/database.php';

$database = new Database();
$game = new HiveGame($database);

$game->move();


header('Location: index.php');


