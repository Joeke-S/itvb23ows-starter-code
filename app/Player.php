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

    public function __construct(string $name, array $pieces = null)
    {
        if ($pieces){
            $this->createHand($pieces);
        } else {
            $this->pieces = self::starterPieces($this);
        }
        $this->name = $name;
        $this->moveCount = 0;
    }

    private function createHand(array $pieces)
    {
        foreach($pieces as $piece) {
            switch ($piece) {
                case 'Q':
                    $this->pieces[] = new Queen($this);
                    break;
                case 'B':
                    $this->pieces[] = new Beetle($this);
                    break;
                case 'S':
                    $this->pieces[] = new Spider($this);
                    break;
                case 'A':
                    $this->pieces[] = new Ant($this);
                    break;
                case 'G':
                    $this->pieces[] = new Grasshopper($this);
                    break;
            }
        }
    }

    private function starterPieces(Player $player): array
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

    public function getHand(): array
    {
        return $this->pieces;
    }

    public function pullPiece($pieceString): ?Insect
    {
        $tiles = $this->getHand();

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
        foreach ($this->getHand() as $piece) {
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
    public function getHandNames(): array
    {
        $handArray = array();
        foreach ($this->pieces as $piece){
            $handArray[] = $piece->getToken();
        }
        return $handArray;
    }
    public function removeInsect($type){
        foreach ($this->pieces as $key => $insect){
            if($insect->getToken() == $type){
                unset($this->pieces[$key]);
                return;
            }
        }
    }

    public function setMoveCount(int $moveCount): void
    {
        $this->moveCount = $moveCount;
    }

}
