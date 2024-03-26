<?php
namespace app;

include_once 'util.php';

require_once 'db/database.php';
include_once 'Player.php';

class HiveGame
{
    private $hand = [];
    private $board = [];
    private $player = 0;

    private Player $currentPlayer;
    private Player $playerOne;
    private Player $playerTwo;
    private $game_id = 0;
    private $pieceMovesTo = [];
    private Database $db;

    function __construct($database)
    {
        $this->db = $database;
        if (!isset($_SESSION['board'])) {
            $_SESSION['board'] = [];
            $_SESSION['hand'] = [0 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3], 1 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3]];
            $_SESSION['player'] = 0;
            $_SESSION['game_id'] = $this->createGame();
            $_SESSION['last_move'] = null;
        }
        $this->playerOne = new Player("pOne");
        $this->playerTwo = new Player("pTwo");


        $this->board = $_SESSION['board'];
        $this->player = $_SESSION['player'];
        $this->hand = $_SESSION['hand'];
        $this->game_id = $_SESSION['game_id'];
        $this->pieceMovesTo = $this->setPieceMovesTo();
        $this->db = new Database();
    }

    private function createGame(){
        return $this->db->game();
    }

    private function setPieceMovesTo(){
        $to = [];
        foreach ($GLOBALS['OFFSETS'] as $pq) {
            foreach (array_keys($this->board) as $pos) {
                $pq2 = explode(',', $pos);
                $to[] = ($pq[0] + $pq2[0]).','.($pq[1] + $pq2[1]);
            }
        }
        $to = array_unique($to);
        if (!count($to)) {
            $to[] = '0,0';
        }
        return $to;
    }
    public function setState($state): void
    {
        list($hand, $board, $player) = unserialize($state);
        $this->hand = $hand;
        $this->board = $board;
        $this->player = $player;
    }
    public function getHand(): array
    {
        return $this->hand;
    }

    public function getBoard(): array
    {
        return $this->board;
    }

    public function getPlayer()
    {
        return $this->player;
    }

    public function getGameId()
    {
        return $this->game_id;
    }
    public function getMovesTo()
    {
        return $this->pieceMovesTo;
    }


    public function getHandPlayer($player): array
    {
        return $this->hand[$player];
    }
    public function move(): void
    {


        $from = $_POST['from'];
        $to = $_POST['to'];

        $player = $_SESSION['player'];
        $board = $_SESSION['board'];
        $hand = $_SESSION['hand'][$player];
        unset($_SESSION['error']);


        if (!isset($board[$from])) {
            $_SESSION['error'] = 'Board position is empty';
        } elseif ($board[$from][count($board[$from]) - 1][0] == $player) {
            if ($hand['Q']) {
                $_SESSION['error'] = "Queen bee is not played";
            } else {
                $tile = array_pop($board[$from]);
                if (!hasNeighBour($to, $board)) {
                    $_SESSION['error'] = "Move would split hive";
                }
                else {
                    $all = array_keys($board);
                    $queue = [array_shift($all)];
                    while ($queue) {
                        $next = explode(',', array_shift($queue));
                        foreach ($GLOBALS['OFFSETS'] as $pq) {
                            list($p, $q) = $pq;
                            $p += $next[0];
                            $q += $next[1];
                            if (in_array("$p,$q", $all)) {
                                $queue[] = "$p,$q";
                                $all = array_diff($all, ["$p,$q"]);
                            }
                        }
                    }
                    if ($all) {
                        $_SESSION['error'] = "Move would split hive";
                    } else {
                        if ($from == $to) {
                            $_SESSION['error'] = 'Tile must move';
                        }
                        elseif (isset($board[$to]) && $tile[1] != "B") {
                            $_SESSION['error'] = 'Tile not empty';
                        }
                        elseif ($tile[1] == "Q" || $tile[1] == "B") {
                            if (!slide($board, $from, $to)) {
                                $_SESSION['error'] = 'Tile must slide';
                            }
                        }
                    }
                }
                if (isset($_SESSION['error'])) {
                    if (isset($board[$from])) {
                        array_push($board[$from], $tile);
                    }
                    else {
                        $board[$from] = [$tile];
                    }
                } else {
                    if (isset($board[$to])) {
                        array_push($board[$to], $tile);
                    }
                    else {
                        $board[$to] = [$tile];
                    }
                    $_SESSION['player'] = 1 - $_SESSION['player'];
                    $result = $this->db->move($_SESSION['game_id'], $from, $to, $_SESSION['last_move'], [$_SESSION['hand'], $_SESSION['board'], $_SESSION['player']]);

                    $_SESSION['last_move'] = $result;
                }
                $_SESSION['board'] = $board;
            }
        } else {
            $_SESSION['error'] = "Tile is not owned by player";
        }

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
        $_SESSION['player'] = 1 - $_SESSION['player'];

    }

    public function play(): void
    {
        $piece = $_POST['piece'];
        $to = $_POST['to'];
        $player = $_SESSION['player'];
        $board = $_SESSION['board'];
        $hand = $_SESSION['hand'][$player];

        if (!$hand[$piece]) {
            $_SESSION['error'] = "Player does not have tile";
            return;
        }
        if (isset($board[$to])) {
            $_SESSION['error'] = 'Board position is not empty';
            return;
        }
        if (count($board) && !hasNeighBour($to, $board)) {
            $_SESSION['error'] = "board position has no neighbour";
            return;
        }
        if (array_sum($hand) < 11 && !neighboursAreSameColor($player, $to, $board)) {
            $_SESSION['error'] = "Board position has opposing neighbour";
            return;
        }
        if (array_sum($hand) <= 8 && $hand['Q']) {
            $_SESSION['error'] = 'Must play queen bee';
            return;
        }
        $_SESSION['board'][$to] = [[$_SESSION['player'], $piece]];
        $_SESSION['hand'][$player][$piece]--;
        $_SESSION['player'] = 1 - $_SESSION['player'];

        $result = $this->db->move($_SESSION['game_id'], $piece, $to, $_SESSION['last_move'], [$_SESSION['hand'], $_SESSION['board'], $_SESSION['player']]);

        $_SESSION['last_move'] = $result;


    }
    public function restart(): void
    {
        $_SESSION['board'] = [];
        $_SESSION['hand'] = [
            0 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3],
            1 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3]
        ];
        $_SESSION['player'] = 0;
        $_SESSION['last_move'] = null;

        $result = $this->db->game();
        $_SESSION['game_id'] = $result;
    }
    public function undo(): void
    {
        $result = $this->db->lastMove($_SESSION['last_move']);

        $_SESSION['last_move'] = $result[5];
        setState($result[6]);
    }
}

