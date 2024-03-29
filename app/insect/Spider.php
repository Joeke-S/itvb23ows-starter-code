<?php

namespace app;

class Spider extends Insect
{
    public function getToken(): string
    {
        return "S";
    }


    public function possibleMoves(array $board, int $player, int $x, int $y): array
    {
        // TODO: Implement possibleMoves() method.
        return  [];
    }

}