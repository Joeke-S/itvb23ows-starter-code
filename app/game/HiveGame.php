<?php

class HiveGame
{
    private $hand;
    private $board;
    private $player;

    public function __construct($hand, $board, $player)
    {
        $this->hand = $hand;
        $this->board = $board;
        $this->player = $player;
    }

    public function getState()
    {
        return serialize([$this->hand, $this->board, $this->player]);
    }

    public function setState($state)
    {
        list($hand, $board, $player) = unserialize($state);
        $this->hand = $hand;
        $this->board = $board;
        $this->player = $player;
    }

    public function getHand()
    {
        return $this->hand;
    }

    public function getBoard()
    {
        return $this->board;
    }

    public function getPlayer()
    {
        return $this->player;
    }
}