<?php

namespace app;

use PHPUnit\Framework\TestCase;

class BoardTest extends TestCase
{

    public function testToArray()
    {
        $boardData = [
            '1,1' => [['0', 'Q'], ['1', 'B']],
            '0,1' => [['0', 'S']],
            '-1,0' => [['1', 'G']]
        ];
        $board = new Board($boardData);
        $this->assertEquals($boardData, $board->toArray());
    }

    public function testGetLastTile()
    {
        $boardData = [
            '1,1' => [['0', 'Q'], ['1', 'B']],
            '0,1' => [['0', 'S']],
            '-1,0' => [['1', 'G']]
        ];
        $board = new Board($boardData);
        $this->assertEquals(['1', 'G'], $board->getLastTile('-1,0'));
        $this->assertEquals(['1', 'B'], $board->getLastTile('1,1')); // When there are atop of one and other
    }

    public function testAllTiles()
    {
        $boardData = [
            '1,1' => [['0', 'Q']],
            '0,1' => [['1', 'B']],
            '-1,0' => [['0', 'S']],
            '2,2' => [['1', 'G']]
        ];
        $board = new Board($boardData);
        $this->assertEquals(['1,1', '0,1', '-1,0', '2,2'], $board->allTiles());
    }

    public function testGetPlayedTiles()
    {
        $boardData = [
            '1,1' => [['0', 'Q'], ['1', 'B']],
            '0,1' => [['0', 'S']],
            '-1,0' => [['1', 'G']]
        ];
        $board = new Board($boardData);
        $this->assertEquals(['1,1', '0,1'], $board->getPlayedTiles(0));
        $this->assertEquals(['-1,0'], $board->getPlayedTiles(1));
        $this->assertEquals([], $board->getPlayedTiles(2)); // No player 2 tiles
    }
    public function testEmptyTile()
    {
        $boardData = [
            '1,1' => [['0', 'Q'], ['1', 'B']],
            '0,1' => [['0', 'S']],
            '-1,0' => [['1', 'G']]
        ];
        $board = new Board($boardData);

        $this->assertFalse($board->emptyTile('1,1'));
        $this->assertTrue($board->emptyTile('2,2'));
    }

    public function testSetTile()
    {
        $board = new Board();
        $board->setTile('1,1', 'Q', 0);
        $this->assertEquals([['0', 'Q']], $board->toArray()['1,1']);
    }

    public function testPushTile()
    {
        $boardData = [
            '1,1' => [['0', 'Q']],
            '0,1' => [['0', 'S']],
            '-1,0' => [['1', 'G']]
        ];
        $board = new Board($boardData);
        $board->pushTile('1,1', 'B', 1);
        $this->assertEquals([['0', 'Q'], ['1', 'B']], $board->toArray()['1,1']);
    }

    public function testPopTile()
    {
        $boardData = [
            '1,1' => [['0', 'Q'], ['1', 'B']],
            '0,1' => [['0', 'S']],
            '-1,0' => [['1', 'G']]
        ];
        $board = new Board($boardData);
        $tile = $board->popTile('1,1');
        $this->assertEquals(['1', 'B'], $tile);
        $this->assertEquals([['0', 'Q']], $board->toArray()['1,1']);
    }
    public function testGetNonEmptyTiles()
    {
        $boardData = [
            '1,1' => [['0', 'Q']],
            '0,1' => [],
            '-1,0' => [['1', 'G']]
        ];
        $board = new Board($boardData);
        $nonEmptyTiles = $board->getNonEmptyTiles();
        $this->assertCount(2, $nonEmptyTiles);
        $this->assertArrayHasKey('1,1', $nonEmptyTiles);
        $this->assertArrayHasKey('-1,0', $nonEmptyTiles);
    }
    public function testIsNeighbour()
    {
        $board = new Board();
        $this->assertTrue($board::isNeighbour('1,1', '0,1'));
        $this->assertFalse($board::isNeighbour('1,1', '2,2'));
    }
    public function testHasNeighbour()
    {
        $boardData = [
            '1,1' => [['0', 'Q']],
            '0,1' => [['1', 'B']],
            '-1,0' => [['1', 'G']]
        ];
        $board = new Board($boardData);
        $this->assertTrue($board->hasNeighbour('0,1'));
        $this->assertFalse($board->hasNeighbour('2,2'));
    }

    public function testNeighboursAreSameColor()
    {
        $boardData = [
            '0,0' => [['0', 'Q']],
            '0,1' => [['1', 'Q']],
            '0,-1' => [['0', 'B']],
        ];
        $board = new Board($boardData);
        $this->assertTrue($board->neighboursAreSameColor(0, '0,-1'));
        $this->assertFalse($board->neighboursAreSameColor(1, '0,1'));
    }

}
