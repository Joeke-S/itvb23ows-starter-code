<?php

namespace app;

include_once 'util.php';

require_once 'db/database.php';
include_once 'Player.php';
require_once 'PlayerHand.php';
require_once 'Board.php';
require_once "GameException.php";

class HiveGame
{
    private Board $board;
    private array $hands;
    private $player = 0;
    private int|null $lastMove;
    private $game_id = 0;
    private $pieceMovesTo = [];
    private Database $db;

    function __construct(Database $database, Board $board = null, int $gameId = null, int $player = 0, array $hands = null,
                         int $lastMove = null)
    {
        $this->player = $player;
        $this->lastMove = $lastMove ?? null;
        $this->db = $database;
        $this->hands = $hands ?? [0 => new PlayerHand(), 1 => new PlayerHand()];
        $this->board = $board ?? new Board();
        $this->game_id = $gameId ?? $this->createGame();
        $this->pieceMovesTo = $this->setPieceMovesTo();
        $this->lastMove = null;
    }

    public static function fromSession($database, array $session): HiveGame
    {
        $hands = null;

        if (isset($session['hand'])) {
            $hands = array_map(function(array $hand) use ($database) {
                return new PlayerHand($hand);
            }, $session['hand']);
        }

        $last_move = null;
        if (isset($session['last_move'])) {
            $last_move = $session['last_move'];
        }

        return new HiveGame(
            database: $database,
            board: new Board($session['board']),
            gameId: $session['game_id'],
            player: $session['player'],
            hands: $hands,
            lastMove: $last_move
        );
    }

    private function createGame()
    {
        return $this->db->game();
    }

    private function setPieceMovesTo()
    {
        $to = [];
        foreach ($GLOBALS['OFFSETS'] as $pq) {
            foreach (array_keys($this->board->getBoard()) as $pos) {
                $pq2 = explode(',', $pos);
                $to[] = ($pq[0] + $pq2[0]) . ',' . ($pq[1] + $pq2[1]);
            }
        }
        $to = array_unique($to);
        if (!count($to)) {
            $to[] = '0,0';
        }
        return $to;
    }

//    public function setState($state): void
//    {
//        list($hands, $board, $player) = unserialize($state);
//        $this->hands = $hands;
//        $this->board = $board;
//        $this->player = $player;
//    }

    public function getHands(): array
    {
        return $this->hands;
    }

    public function getBoard()
    {
        return $this->board;
    }

    public function getPlayer()
    {
        return $this->player;
    }

    public function getOtherPlayer(): int
    {
        return 1 - $this->player;
    }

    public function getGameId()
    {
        return $this->game_id;
    }

    public function getMovesTo()
    {
        return $this->pieceMovesTo;
    }

    public function getHandPlayer($player)
    {
        return $this->hands[$player];
    }

    private function moveTile(string $position, array $tile)
    {
        if (!$this->board->emptyTile($position)) {
            $this->board->pushTile($position, $tile[1], $tile[0]);
        } else {
            $this->board->setTile($position, $tile[1], $tile[0]);
        }
    }

    private function checkTile(string $from)
    {
        if ($this->board->emptyTile($from)) {
            throw new GameException('Board position is empty');
        } elseif ($this->board->getLastTile($from)[0] != $this->player) {
            throw new GameException("Tile is not owned by player");
        } elseif ($this->getHandPlayer($this->player)->hasPiece('Q')) {
            throw new GameException("Queen bee is not played");
        }
    }

    private function checkHive(string $to)
    {
        if (!$this->board->hasNeighBour($to)) {
            throw new GameException("Move would split hive");
        }

        $all = $this->board->allTiles();
        $queue = [array_shift($all)];
        while ($queue) {
            $next = explode(',', array_shift($queue));
            foreach (Board::$OFFSETS as $pq) {
                list($p, $q) = $pq;
                $p .= $next[0];
                $q .= $next[1];
                if (in_array("$p,$q", $all)) {
                    $queue[] = "$p,$q";
                    $all = array_diff($all, ["$p,$q"]);
                }
            }
        }
        if ($all) {
            throw new GameException("Move would split hive");
        }
    }

    private function checkDestination(string $from, string $to, string $type)
    {
        if ($from == $to) {
            throw new GameException('Tile must move');
        } elseif (!$this->board->emptyTile($to) && $type != "B") {
            throw new GameException('Tile not empty');
        } elseif ($type == "Q" || $type == "B") {
            if (!$this->board->slide($from, $to)) {
                throw new GameException('Tile must slide');
            }
        }
    }

    public function move(string $from, string $to)
    {
        $tile = null;
        try {
            $this->checkTile($from);
            $tile = $this->board->popTile($from);
            $this->checkHive($from);
            $this->checkDestination($from, $to, $tile[1]);

            $this->moveTile($to, $tile);
        } catch (GameException $e) {
            if ($tile) {
                if (!$this->board->emptyTile($from)) {
                    $this->board->pushTile($from, $tile[1], $tile[0]);
                } else {
                    $this->board->setTile($from, $tile[1], $tile[0]);
                }
                $this->moveTile($from, $tile);
            }

            throw $e;
        }

        return $this->db->move($this->game_id, $from, $to, $this->lastMove, $this->getState());

    }

    public function pass(): void
    {


        $result = $this->db->move(
            $_SESSION['game_id'],
            null, null,
            $_SESSION['last_move'],
            [$_SESSION['hand'], $_SESSION['board'], $_SESSION['player']]
        );

        $_SESSION['last_move'] = $result;
        $_SESSION['player'] = $this->getOtherPlayer();

    }


    public function play(string $piece, string $to)
    {
        $hand = $this->getHandPlayer($this->player);

        if (!$hand->hasPiece($piece)) {
            throw new GameException("Player does not have tile");
        }
        if ($hand->sum() <= 8 && $hand['Q']) {
            throw new GameException('Must play queen bee');
        }
        $this->checkPlayRules($to);

        $this->board->setTile($to, $piece, $this->getPlayer());
        $this->getHandPlayer($this->player)->removePiece($piece);

        return $this->db->move($this->game_id, $piece, $to, $this->lastMove, $this->getState());
    }
    public function getState()
    {
        $hands = array_map(function (PlayerHand $hand) {
            return $hand->getHand();
        }, $this->getHands());

        return serialize([$hands, $this->getBoard()->getBoard(), $this->getPlayer()]);
    }
    public function checkPlayRules($to): void
    {
        if (!$this->board->emptyTile($to)) {
            throw new GameException('Board position is not empty');
        } elseif ($this->board->boardCount() && !$this->board->hasNeighBour($to)) {
            throw new GameException("board position has no neighbour");
        } elseif ($this->getHandPlayer($this->getPlayer())->sum() < 11 && !$this->board->neighboursAreSameColor($this->getPlayer(), $to)) {
            throw new GameException("Board position has opposing neighbour");
        }
    }



    public function undo()
    {
        $result = $this->db->lastMove($_SESSION['last_move']);
        $lastMove = $result[5];
        setState($result[6]);
        return $lastMove;

    }
}

