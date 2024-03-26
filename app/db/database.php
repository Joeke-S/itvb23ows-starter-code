<?php
namespace app;


use mysqli;

class Database
{
    private ?mysqli $connection;

    function __construct()
    {
        $connection = new mysqli('localhost', 'root', 'root', 'hive');
        if ($connection->connect_error) {
            die('Connect Error (' . $connection->connect_errno . ') ' . $connection->connect_error);
        }

        $this->connection = $connection;

    }

    public function getConnection(): mysqli
    {
        return $this->connection;
    }



    public function move($game_id, $from, $to, $last_move, $state)
    {
        $database = $this->getConnection();

        $stmt = $database->prepare(
            'INSERT INTO moves (
                   game_id, type, move_from, move_to, previous_id, state) VALUES (?, "move", ?, ?, ?, ?)'
        );
        $string = serialize($state);
        $stmt->bind_param('issis', $game_id, $from, $to, $last_move, $string);
        $stmt->execute();

        return $database->insert_id;
    }

    public function game()
    {
        $database = $this->getConnection();

        $stmt = $database->prepare('INSERT INTO games VALUES ()');
        $stmt->execute();

        return $database->insert_id;
    }

    public function lastMove($lastMove)
    {
        $database = $this->getConnection();

        $stmt = $database->prepare(
            'SELECT * FROM moves WHERE id = ' . $lastMove
        );
        $stmt->execute();
        $result = $stmt->get_result()->fetch_array();

        $delstmt = $database->prepare('DELETE FROM moves WHERE id=' . $lastMove);
        $delstmt->execute();


        return $result;
    }
    public function gameHistory($gameId)
    {
        $database = $this->getConnection();

        $stmt = $database->prepare(
            'SELECT * FROM moves WHERE game_id = '.$gameId);
        $stmt->execute();

        return $stmt->get_result();
    }

}

