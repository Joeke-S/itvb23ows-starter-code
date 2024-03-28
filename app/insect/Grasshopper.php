<?php
namespace app;

include_once __DIR__."/../Player.php";
require_once "Insect.php";

class Grasshopper extends Insect
{
    public function getToken(): string
    {
        return "G";
    }
    public function possibleMoves($board, Player $player, $x, $y): array
    {
        $pos = [];
        $offsets = $GLOBALS['OFFSETS'];

        foreach ($offsets as list($dx, $dy)){
            $jumped = false;
            $px = $x + $dx;
            $py = $y + $dy;

            while (isset($board[$px.",".$py])){
                $jumped = true;
                $px += $dx;
                $py += $dy;
            }

            if ($jumped) {
                $pos[] = [$px, $py];
            }
        }

        return $pos;

    }

}