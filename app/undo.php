<?php

include_once 'HiveGame.php';

$game = new HiveGame();

$game->undo();
header('Location: index.php');

