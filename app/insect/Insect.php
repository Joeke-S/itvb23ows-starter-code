<?php

namespace app;

include_once __DIR__."/../Player.php";

abstract class Insect
{
    public function __construct()
    {}

    abstract public function getToken(): string;


    public function canMoveTo(array $board, int $player, int $fromX, int $fromY, int $toX, int $toY): bool
    {
        $movePositions = $this->possibleMoves($board, $player, $fromX, $fromY);

        foreach ($movePositions as list($posX, $posY)) {
            if ($toX == $posX && $toY == $posY) {
                return true;
            }
        }

        return false;
    }



    abstract public function possibleMoves(array $board, int $player, int $x, int $y): array;


}