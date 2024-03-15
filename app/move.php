<?php

include_once 'HiveGame.php';

$game = new HiveGame();

$game->move();


header('Location: index.php');


