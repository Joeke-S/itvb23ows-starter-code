<?php

namespace app;

include_once __DIR__."/../Player.php";

abstract class Insect
{
    protected Player $owner;

    public function __construct(Player $owner)
    {
        $this->owner = $owner;
    }

    abstract public function getToken(): string;

    public function getOwner()
    {
        return $this->owner;
    }

    public function canMoveTo(array $board, Player $player, int $fromX, int $fromY, int $toX, int $toY): bool
    {
        $movePositions = $this->possibleMoves($board, $player, $fromX, $fromY);

        foreach ($movePositions as list($posX, $posY)) {
            if ($toX == $posX && $toY == $posY) {
                return true;
            }
        }

        return false;
    }



    abstract public function possibleMoves(array $board, Player $player, int $x, int $y): array;


}