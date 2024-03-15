<?php
use PHPUnit\Framework\TestCase;
use HiveGame;

final class HiveGameTest extends TestCase
{

    public function testCreateGame()
    {

    }
    public function testSetPieceMovesTo()
    {

    }

    public function testSetState()
    {

    }
    public function testGetHand()
    {
        $game = new HiveGame();

        $this->assertSame(
            [
                0 =>
                    ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3],
                1 =>
                    ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3]
            ],
            $game->getHand()
        );

    }

    public function testGetBoard()
    {
        $game = new HiveGame();

        $this->assertSame([], $game->getBoard());

    }

    public function testGetPlayer()
    {
        $game = new HiveGame();

        $this->assertSame([], $game->getPlayer());
    }
    public function testGetGameId()
    {
        $game = new HiveGame();

        $this->assertSame([], $game->getGameId());
    }
    public function testGetMovesTo()
    {

    }
    public function testGetHandPlayer()
    {

    }
    public function testMove()
    {

    }

    public function testPass()
    {

    }

    public function testPlay()
    {

    }

    public function testRestart()
    {

    }

    public function testUndo()
    {

    }
}