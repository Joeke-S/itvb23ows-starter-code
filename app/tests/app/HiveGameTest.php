<?php

namespace app;
use PHPUnit\Framework\TestCase;
include_once __DIR__.'/../../db/database.php';
require_once __DIR__."/../../HiveGame.php";
final class HiveGameTest extends TestCase
{


    public function testGetBoard()
    {
        $database = self::createStub(Database::class);
        $database->method("game")->willReturn(0);
        $game = new HiveGame($database);

        $this->assertSame([], $game->getBoard()->toArray());

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
