<?php

namespace app;
include_once 'db/database.php';
class GamePrinter
{

    private $db;
    private HiveGame $game;

    public function __construct($game)
    {
        $this->game = $game;
        $this->db = new Database();
    }

    public function printMoveHistory(){

        $result = $this->db->gameHistory($this->game->getGameId());

        while ($row = $result->fetch_array()) {
            echo '<li>'.$row[2].' '.$row[3].' '.$row[4].'</li>';
        }
    }

    public function printHand($player){

        foreach ($this->game->getOneOfPlayers($player)->getHand() as $ct) {
            echo '<div class="tile player' . $player . '"><span>' . $ct->getToken() . "</span></div> ";
        }


    }

    public function printBoard(){
        $min_p = 1000;
        $min_q = 1000;
        foreach ($this->game->getBoard()->toArray() as $pos => $tile) {
            $pq = explode(',', $pos);
            if ($pq[0] < $min_p) {
                $min_p = $pq[0];
            }
            if ($pq[1] < $min_q) {
                $min_q = $pq[1];
            }
        }
        foreach (array_filter($this->game->getBoard()->toArray()) as $pos => $tile) {
            $pq = explode(',', $pos);
            $pq[0];
            $pq[1];
            $h = count($tile);
            echo '<div class="tile player';
            echo $tile[$h-1][0];
            if ($h > 1) echo ' stacked';
            echo '" style="left: ';
            echo ($pq[0] - $min_p) * 4 + ($pq[1] - $min_q) * 2;
            echo 'em; top: ';
            echo ($pq[1] - $min_q) * 4;
            echo "em;\">($pq[0],$pq[1])<span>";
            echo $tile[$h-1][1];
            echo '</span></div>';
        }
    }

    public function printMoveTo(HiveGame $game){
        foreach ($game->getValidMoveOptions() as $pos) {
            echo "<option value=\"$pos\">$pos</option>";
        }
    }

    public function printMoveFrom(HiveGame $game){
        foreach($game->getBoard()->getPlayedTiles($game->getPlayer()) as $pos){
            echo "<option value=\"$pos\">$pos</option>";
        }
    }

    public function printPlayTo(){
        foreach ($this->game->getMovesTo() as $pos) {
            if (empty($this->game->getBoard())){
                echo "<option value=\"$pos\">$pos</option>";
            }
            elseif(!in_array($pos, $this->game->getBoard()->toArray())) {
                echo "<option value=\"$pos\">$pos</option>";
            }
        }
    }

    public function printPiecesAvailable(){
        $vals = [];
        foreach ($this->game->getOneOfPlayers($this->game->getPlayer())->getHand() as $ct) {
            if (in_array($ct->getToken(), $vals)) {
                continue;
            }
            $val = $ct->getToken();
            $vals[] = $val;
            echo "<option value=\"$val\">$val</option>";
        }
    }
}