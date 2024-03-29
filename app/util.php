<?php

namespace app;

$GLOBALS['OFFSETS'] = [[0, 1], [0, -1], [1, 0], [-1, 0], [-1, 1], [1, -1]];

function setState($state)
{
    echo 'i got here';
    list($a, $b, $c) = unserialize($state);
    echo '<br>';
    echo $a;
    echo '<br>';
    echo $b;
    echo '<br>';
    echo $c;
    $_SESSION['hand'] = $a;
    $_SESSION['board'] = $b;
    $_SESSION['player'] = $c;
}




