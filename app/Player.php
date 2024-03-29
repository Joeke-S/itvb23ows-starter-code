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

    private int $name;


    public function __construct(int $name, array $pieces = null)
    {
        $this->name = $name;
        if ($pieces != null){
            $this->createHand($pieces);
            return;
        }
        $this->pieces = array(
            new Queen(),
            new Beetle(),
            new Beetle(),
            new Spider(),
            new Spider(),
            new Ant(),
            new Ant(),
            new Ant(),
            new Grasshopper(),
            new Grasshopper(),
            new Grasshopper()
        );

    }

    private function createHand(array $pieces): void
    {
        foreach($pieces as $piece) {
            switch ($piece) {
                case 'Q':
                    $this->pieces[] = new Queen();
                    break;
                case 'B':
                    $this->pieces[] = new Beetle();
                    break;
                case 'S':
                    $this->pieces[] = new Spider();
                    break;
                case 'A':
                    $this->pieces[] = new Ant();
                    break;
                case 'G':
                    $this->pieces[] = new Grasshopper();
                    break;
            }
        }
    }

//    private function starterPieces(): array
//    {
//
//    }

    public function getHand(): array
    {
        return $this->pieces;
    }
    public function getHandArr(): array
    {
        $handArray = array();
        foreach ($this->pieces as $insect){

            $handArray[] = $insect->getToken();
        }
        return $handArray;
    }

    public function pullPiece($pieceString): ?Insect
    {
        $tiles = $this->getHand();

        for ($i = 0; $i < count($tiles); $i++) {
            $piece = $tiles[$i];

            if ($piece->getToken() != $pieceString) {
                continue;
            }

            unset($tiles[$i]);
            $this->pieces = array_values($tiles);
            return $piece;
        }

        return null;
    }
    public function hasPiece($insect):bool{
        foreach ($this->pieces as $handInsect){
            switch ($insect){
                case 'Q':
                    if($handInsect instanceof Queen){
                        return true;
                    }
                    break;
                case 'B':
                    if($handInsect instanceof Beetle){
                        return true;
                    }
                    break;
                case 'S':
                    if($handInsect instanceof Spider){
                        return true;
                    }
                    break;
                case 'A':
                    if($handInsect instanceof Ant){
                        return true;
                    }
                    break;
                case 'G':
                    if($handInsect instanceof Grasshopper){
                        return true;
                    }
                    break;
            }
        }
        return false;
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

    public function getName(): int
    {
        return $this->name;
    }

    public function getHandTokens(): array
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


}
