<?php

namespace app;

use PHPUnit\Framework\TestCase;
require_once __DIR__."/../../PlayerHand.php";
class PlayerHandTest extends TestCase
{

    public function testHasPiece()
    {
        $hand = new PlayerHand(["Q" => 1, "B" => 1, "S" => 1, "A" => 2, "G" => 2]);

        $this->assertTrue($hand->hasPiece("Q"));
        $this->assertTrue($hand->hasPiece("B"));
        $this->assertFalse($hand->hasPiece("X"));

    }

    public function testRemovePiece()
    {
        $hand = new PlayerHand(["Q" => 1, "B" => 1, "S" => 1, "A" => 2, "G" => 2]);

        $hand->removePiece("Q");
        $this->assertFalse($hand->hasPiece("Q"));

        $hand->removePiece("X");
        $this->assertFalse($hand->hasPiece("X"));

    }

    public function testPlayedPieces()
    {
        $hand = new PlayerHand(["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 2]);
        $playedPieces = $hand->playedPieces();

        $this->assertEquals(["G" => 1], $playedPieces);

    }

    public function testSum()
    {
        $hand = new PlayerHand(["Q" => 1, "B" => 1, "S" => 1, "A" => 2, "G" => 2]);
        $this->assertEquals(7, $hand->sum());

        $hand = new PlayerHand(["Q" => 0, "B" => 1, "S" => 1, "A" => 2, "G" => 2]);
        $this->assertEquals(6, $hand->sum());
    }

    public function testGetRemainingPieces()
    {
        $hand = new PlayerHand(["Q" => 1, "B" => 1, "S" => 1, "A" => 2, "G" => 2]);
        $hand->removePiece("B");
        $remainingPieces = $hand->getRemainingPieces();

        $this->assertEquals(["Q" => 1, "S" => 1, "A" => 2, "G" => 2], $remainingPieces);
    }

    public function testGetHand()
    {
        $initialHand = ["Q" => 1, "B" => 1, "S" => 1, "A" => 2, "G" => 2];
        $hand = new PlayerHand($initialHand);

        $this->assertEquals($initialHand, $hand->getHand());
    }


}
