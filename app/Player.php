<?php

namespace app;


require_once 'insect/Queen.php';
require_once 'insect/Ant.php';
require_once 'insect/Grasshopper.php';
require_once 'insect/Beetle.php';
require_once 'insect/Insect.php';
require_once 'insect/Spider.php';


class Player
{
    private array $pieces;

    private string $name;

    private int $moveCount;

    public function __construct(string $name)
    {
        $this->pieces = self::starterPieces($this);
        $this->name = $name;
        $this->moveCount = 0;
    }

    private static function starterPieces(Player $player): array
    {
        return array(
            new Queen($player),
            new Beetle($player),
            new Beetle($player),
            new Spider($player),
            new Spider($player),
            new Ant($player),
            new Ant($player),
            new Ant($player),
            new Grasshopper($player),
            new Grasshopper($player),
            new Grasshopper($player),
        );
    }

    public function getPieces(): array
    {
        return $this->pieces;
    }

    public function pullPiece($pieceString): ?Insect
    {
        $tiles = $this->getPieces();

        for ($i = 0; $i < count($tiles); $i++) {
            $piece = $tiles[$i];

            if ($piece->getMarker() != $pieceString) {
                continue;
            }

            unset($tiles[$i]);
            $this->pieces = array_values($tiles);
            return $piece;
        }

        return null;
    }

    public function hasQueenInHand(): bool
    {
        foreach ($this->getPieces() as $piece) {
            if ($piece::class == Queen::class) {
                return true;
            }
        }

        return false;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function incrementMoveCount()
    {
        $this->moveCount++;
    }

    public function getMoveCount(): int
    {
        return $this->moveCount;
    }

    public function setMoveCount(int $moveCount): void
    {
        $this->moveCount = $moveCount;
    }

}
