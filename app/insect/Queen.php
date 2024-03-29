<?php
namespace app;

require_once "Insect.php";

class Queen extends Insect
{
    public function getToken(): string
    {
        return "Q";
    }


    public function possibleMoves(array $board, int $player, int $x, int $y): array
    {
        // TODO: Implement possibleMoves() method.
        return  [];
    }
}