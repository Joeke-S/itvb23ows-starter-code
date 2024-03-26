<?php

namespace app;
use PHPUnit\Framework\TestCase;
include_once __DIR__.'/../../db/database.php';
require_once __DIR__."/../../HiveGame.php";
final class HiveGameTest extends TestCase
{

    public function testGetHand()
    {
        $database = self::createStub(Database::class);
        $database->method("game")->willReturn(0);
        $game = new HiveGame($database);

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
    public function testSetState()
    {

        $database = self::createStub(Database::class);
        $database->method("game")->willReturn(0);
        $game = new HiveGame($database);

        $sampleState = serialize([
            [0 => ["Q" => 0, "B" => 2, "S" => 2, "A" => 3, "G" => 3], 1 => ["Q" => 0, "B" => 2, "S" => 2, "A" => 3, "G" => 3]],
            ['PLOP'],
            1
        ]);

        $game->setState($sampleState);

        $this->assertEquals($sampleState, serialize([$game->getHand(), $game->getBoard(), $game->getPlayer()]));
    }

    public function testGetBoard()
    {
        $database = self::createStub(Database::class);
        $database->method("game")->willReturn(0);
        $game = new HiveGame($database);

        $this->assertSame([], $game->getBoard());

    }

    public function testGetPlayer()
    {
        $database = self::createStub(Database::class);
        $database->method("game")->willReturn(0);
        $game = new HiveGame($database);

        $this->assertSame(0, $game->getPlayer());
    }
    public function testGetGameId()
    {
        $database = self::createStub(Database::class);
        $database->method("game")->willReturn(0);
        $game = new HiveGame($database);

        $this->assertSame(0, $game->getGameId());
    }

}
