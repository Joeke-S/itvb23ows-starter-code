<?php
namespace app;

require_once "Insect.php";

class Beetle extends Insect
{
    public function getToken(): string
    {
        return "B";
    }


    public function possibleMoves(array $board, int $player, int $x, int $y): array
    {
        // TODO: Implement possibleMoves() method.
        return  [];
    }

}