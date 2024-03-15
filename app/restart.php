<?php

include_once 'HiveGame.php';

$game = new HiveGame();

$game->restart();

header('Location: index.php');