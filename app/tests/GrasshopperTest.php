<?php
namespace app;
require_once __DIR__."/../Player.php";
require_once __DIR__."/../insect/Grasshopper.php";
use PHPUnit\Framework\TestCase;

class GrasshopperTest extends TestCase
{


    public function testGetToken()
    {
        $grasHopper = new Grasshopper();
        self::assertSame("G", $grasHopper->getToken());

    }

    public function testHopOneStep(){
        $board = [];

        $grasshopper = new Grasshopper();
        $board["0,0"] = $grasshopper->getToken();

        $canMove = $grasshopper->canMoveTo($board, 0, 0, 0, 0, 1);

        self::assertFalse($canMove);

    }

    public function testCanMoveToSelf()
    {
        $board = [];

        $grasshopper = new Grasshopper();
        $board["0,0"] = $grasshopper->getToken();

        $canMove = $grasshopper->canMoveTo($board, 0, 0, 0, 0, 0);

        self::assertFalse($canMove);
    }

}
