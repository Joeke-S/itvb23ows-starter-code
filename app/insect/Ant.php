<?php

namespace app;
require_once "Insect.php";

class Ant extends Insect
{
    public function getToken(): string
    {
        return "A";
    }


    public function possibleMoves(array $board, int $player, int $x, int $y): array
    {
        // TODO: Implement possibleMoves() method.
        return  [];
    }

}