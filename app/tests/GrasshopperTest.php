<?php
namespace app;
require_once __DIR__."/../Player.php";
require_once __DIR__."/../insect/Grasshopper.php";
use PHPUnit\Framework\TestCase;

class GrasshopperTest extends TestCase
{


    public function testGetToken()
    {
        $playerMock = new Player("p1");

        $grasHopper = new Grasshopper($playerMock);
        self::assertSame("G", $grasHopper->getToken());

    }

    public function testHopOneStep(){
        $playerMock = new Player("p1");
        $board = [];

        $grasshopper = new Grasshopper($playerMock);
        $board["0,0"] = $grasshopper->getToken();

        $canMove = $grasshopper->canMoveTo($board, $playerMock, 0, 0, 0, 1);

        self::assertFalse($canMove);

    }

    public function testCanMoveToSelf()
    {
        $playerMock = new Player("p1");
        $board = [];

        $grasshopper = new Grasshopper($playerMock);
        $board["0,0"] = $grasshopper->getToken();

        $canMove = $grasshopper->canMoveTo($board, $playerMock, 0, 0, 0, 0);

        self::assertFalse($canMove);
    }

}
