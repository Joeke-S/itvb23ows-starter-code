<?php

namespace app;

class Board
{
    public static $OFFSETS = [
        [0, 1],
        [0, -1],
        [1, 0],
        [-1, 0],
        [-1, 1],
        [1, -1]
    ];
    private array $board;

    public function __construct(array $board = [])
    {
        $this->board = $board;
    }
    public function toArray(): array
    {
        return $this->board;
    }

    public function getLastTile($pos)
    {
        return end($this->board[$pos]);
    }

    public function allTiles(): array
    {
        return array_keys($this->board);
    }

    public function getPlayedTiles(int $player): array
    {
        $tiles = array_filter($this->board, function ($value) use ($player) {
            return is_array($value) && isset($value[0]) && is_array($value[0]) && $value[0][0] == $player;
        });
        return array_keys($tiles);
    }
    public function emptyTile(string $position): bool
    {
        return !isset($this->board[$position]);
    }

    public function setTile(string $position, string $piece, int $player)
    {
        $this->board[$position] = array(array($player, $piece));
    }

    public function pushTile(string $position, string $piece, int $player)
    {
        $this->board[$position][] = array($player, $piece);
    }

    public function popTile(string $position): array
    {
        return array_pop($this->board[$position]);
    }

    public function getNonEmptyTiles()
    {
        return array_filter($this->board, function ($tileStack) {
            return !empty($tileStack);
        });
    }

    public static function isNeighbour($a, $b)
    {
        $a = explode(',', $a);
        $b = explode(',', $b);
        if (
            ($a[0] == $b[0] && abs($a[1] - $b[1]) == 1) ||
            ($a[1] == $b[1] && abs($a[0] - $b[0]) == 1) ||
            ($a[0] + $a[1] == $b[0] + $b[1])
        ) {
            return true;
        }
        return false;
    }

    public function hasNeighBour($a)
    {
        foreach (array_keys($this->board) as $b) {
            if (self::isNeighbour($a, $b)) {
                return true;
            }
        }
        return false;
    }

    public function neighboursAreSameColor($player, $a)
    {
        foreach ($this->board as $b => $st) {
            if (!$st) {
                continue;
            }
            $c = $st[count($st) - 1][0];
            if ($c != $player && self::isNeighbour($a, $b)) {
                return false;
            }
        }
        return true;
    }

    public static function len($tile)
    {
        return $tile ? count($tile) : 0;
    }

    public function boardCount()
    {
        return count($this->board);
    }

    public function slide(string $from, string $to): bool
    {
        if (!$this->hasNeighbour($to) || !$this->isNeighbour($from, $to)) {
            return false;
        }
        $b = explode(',', $to);
        $common = [];
        foreach (self::$OFFSETS as $pq) {
            $p = $b[0] + $pq[0];
            $q = $b[1] + $pq[1];
            if ($this->isNeighbour($from, $p . "," . $q)) {
                $common[] = $p . "," . $q;
            }
        }
        if (count($this->board) == 2 && $this->emptyTile($common[0]) && $this->emptyTile($common[0]))
        {
            return false;
        }
        return true;
    }


}